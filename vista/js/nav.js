document.addEventListener('DOMContentLoaded', () => {
  const menu = document.getElementById('menuOpciones');
  if (!menu) return;

  const current = (location.pathname.split('/').pop() || '').toLowerCase();

  
  menu.querySelectorAll('.menu-link.menu-toggle').forEach(a => {
    a.addEventListener('click', (e) => {
      e.preventDefault();
      const li = a.closest('.menu-item');
      if (li) li.classList.toggle('open');
    });
  });

  
  const links = Array.from(menu.querySelectorAll('a.menu-link[href]'))
    .filter(a => a.getAttribute('href') && a.getAttribute('href') !== 'javascript:void(0);');

  let activeLink = null;

  for (const a of links) {
    const href = (a.getAttribute('href') || '').toLowerCase();
    if (!href) continue;

    if (href === current || href.endsWith(current)) {
      activeLink = a;
      break;
    }
  }

  if (activeLink) {
    const li = activeLink.closest('.menu-item');
    if (li) li.classList.add('active');

    const parentItem = activeLink.closest('.menu-sub')?.closest('.menu-item');
    if (parentItem) parentItem.classList.add('open');
  }
});