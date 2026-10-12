// Reads a designer brief (PDF) with macOS PDFKit, nothing to install.
//
//   swift brief.swift text <brief.pdf> [--magenta]   text runs: page, font, size, colour, position
//   swift brief.swift render <brief.pdf> <out-dir> [width]   pages as PNG (default 1024 px wide)
//
// stoz briefs are 1024 pt wide, so sizes and positions are px at the design width.
// Magenta runs are instructions to us (fonts, colours, links, "PDF folgt"), not content.
import AppKit
import PDFKit

let args = CommandLine.arguments
guard args.count >= 3, let doc = PDFDocument(url: URL(fileURLWithPath: args[2])) else {
  print("Usage: swift brief.swift text <brief.pdf> [--magenta] | render <brief.pdf> <out-dir> [width]")
  exit(1)
}

func hex(_ color: NSColor?) -> String {
  guard let c = color?.usingColorSpace(.sRGB) else { return "#000000" }
  return String(format: "#%02x%02x%02x", Int(c.redComponent * 255), Int(c.greenComponent * 255), Int(c.blueComponent * 255))
}

func isMagenta(_ color: NSColor?) -> Bool {
  guard let c = color?.usingColorSpace(.sRGB) else { return false }
  return c.redComponent > 0.75 && c.blueComponent > 0.45 && c.greenComponent < 0.35
}

switch args[1] {
case "text":
  let onlyMagenta = args.contains("--magenta")
  for i in 0..<doc.pageCount {
    let page = doc.page(at: i)!
    let height = page.bounds(for: .mediaBox).height
    print("=== page \(i + 1)  (\(Int(page.bounds(for: .mediaBox).width)) × \(Int(height)))")
    guard let text = page.attributedString else { continue }
    text.enumerateAttributes(in: NSRange(location: 0, length: text.length)) { attrs, range, _ in
      let run = (text.string as NSString).substring(with: range).replacingOccurrences(of: "\n", with: " / ")
      if run.trimmingCharacters(in: .whitespaces).isEmpty { return }
      let color = attrs[.foregroundColor] as? NSColor
      if onlyMagenta && !isMagenta(color) { return }
      let font = attrs[.font] as? NSFont
      let box = page.selection(for: range)?.bounds(for: page) ?? .zero
      print(String(format: "%@ %.1f %@ | x=%.0f y=%.0f | %@", font?.fontName ?? "?", font?.pointSize ?? 0, hex(color), box.minX, height - box.maxY, String(run.prefix(90))))
    }
  }
case "render":
  guard args.count >= 4 else { print("render needs an output directory"); exit(1) }
  let width = args.count >= 5 ? CGFloat(Double(args[4]) ?? 1024) : 1024
  try? FileManager.default.createDirectory(atPath: args[3], withIntermediateDirectories: true)
  for i in 0..<doc.pageCount {
    let page = doc.page(at: i)!
    let bounds = page.bounds(for: .mediaBox)
    let scale = width / bounds.width
    let image = NSImage(size: NSSize(width: bounds.width * scale, height: bounds.height * scale))
    image.lockFocus()
    NSColor.white.set()
    NSRect(origin: .zero, size: image.size).fill()
    let context = NSGraphicsContext.current!.cgContext
    context.scaleBy(x: scale, y: scale)
    page.draw(with: .mediaBox, to: context)
    image.unlockFocus()
    let path = "\(args[3])/page-\(i + 1).png"
    try! NSBitmapImageRep(data: image.tiffRepresentation!)!.representation(using: .png, properties: [:])!.write(to: URL(fileURLWithPath: path))
    print(path)
  }
default:
  print("Unknown command \(args[1]); use text or render")
  exit(1)
}
