// Fade-in on scroll (module:scroll-reveal), without a library. Elements with
// data-reveal, and the children of data-reveal-children (staggered), fade up once
// when they enter the viewport. CSS hides them only while html has .reveal-ready,
// which the head script sets and removes again if this module never starts.

window.revealStarted = true;

const root = document.documentElement;
const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const targets = [
  ...document.querySelectorAll('[data-reveal]'),
  ...[...document.querySelectorAll('[data-reveal-children]')].flatMap((parent) =>
    [...parent.children].map((child, index) => {
      child.style.transitionDelay = `${index * 100}ms`;
      return child;
    }),
  ),
];

if (reduced || !('IntersectionObserver' in window)) {
  root.classList.remove('reveal-ready');
} else {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        }
      });
    },
    { rootMargin: '0px 0px -10% 0px' },
  );

  targets.forEach((el) => observer.observe(el));
}
