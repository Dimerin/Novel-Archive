window.addEventListener('load', init);

function init() {
    const forgotPwdForm = document.getElementById('forgotPwdForm');
    forgotPwdForm.addEventListener('submit', forgotPwd);
}

async function forgotPwd(event){
    event.preventDefault();
}