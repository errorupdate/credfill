
const changeSubNav = (clickSubNav, subPage) => {
    const subNav = document.querySelectorAll('.sub-nav-btn');
    subNav.forEach((subNavitem) => {
        subNavitem.classList.remove('active');
    });
    subPage.forEach((subPageItem) => {
        subPageItem.classList.remove('active');
    });
    const subPageId = clickSubNav.getAttribute('data-sub-page');
    const subPageToShow = document.querySelector(subPageId);
    subPageToShow.classList.add('active');
    clickSubNav.classList.add('active');
    window.scrollTo(0, 0, 'smooth')
}
// document.querySelectorAll('.sub-nav-btn');
document.querySelectorAll('.sub-nav-btn').forEach((subNavitem) => {
    subNavitem.addEventListener('click', () => changeSubNav(subNavitem, document.querySelectorAll('.sub-page') ));
});