window.addEventListener('load', init);

function init() {
    const resetPwdForm = document.getElementById('resetPwdForm');
    resetPwdForm.addEventListener('submit', resetPwd);
}

async function resetPwd(event) {
    event.preventDefault();
}