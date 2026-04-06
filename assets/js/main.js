function updateDateTime() {
    const now = new Date();
    const elem = document.getElementById('currentDateTime');
    if (elem) elem.innerText = now.toLocaleString();
}
setInterval(updateDateTime, 1000);

document.getElementById('darkModeToggle')?.addEventListener('change', e => {
    document.body.classList.toggle('dark-mode', e.target.checked);
});