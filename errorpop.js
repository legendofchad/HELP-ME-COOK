const openModalButtons = document.querySelectorAll('[data-modal-target]')
const closeModalButtons = document.querySelectorAll('[data-close-button]')
const overlay = document.getElementById('overlay')

openModalButtons.forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.querySelector(button.dataset.modalTarget)
        openModal(modal)
    })
})

overlay.addEventListener('click', () => {
    const modals = document.querySelectorAll('.modal.active')
    modals.forEach(modal => {
        closeModal(modal)
    })
})

closeModalButtons.forEach(button => {
    button.addEventListener('click', () => {
        const modal = button.closest('.modal')
        closeModal(modal)
    })
})

function openModal(modal) {
    if (modal == null) return
    modal.classList.add('active')
    overlay.classList.add('active')
}

function closeModal(modal) {
    if (modal == null) return
    modal.classList.remove('active')
    overlay.classList.remove('active')
}

function openErrorModal() {
    const errorModal = document.getElementById('error-modal');
    openModal(errorModal);
}

// Check if the form was submitted for login
if (isset($_POST['login'])) {
    // Handle login
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $auth_result = authenticate_user($username, $password);

    if ($auth_result === true) {
        // Redirect to the desired page after successful login
        header("Location: signup.html");
        exit();
    } else {
        // Show error modal
        openErrorModal();
    }
}