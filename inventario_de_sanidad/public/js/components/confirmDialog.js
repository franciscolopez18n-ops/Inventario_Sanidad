export function showConfirmDialog(event, dialogId, action) {
    event.preventDefault();

    const dialog = document.getElementById(dialogId);
    const btnConfirm = dialog.querySelector('[data-action="confirm"]');
    const btnCancel = dialog.querySelector('[data-action="cancel"]');

    dialog.showModal();

    btnConfirm.onclick = () => {
        action();
        dialog.close();
    };

    btnCancel.onclick = () => {
        dialog.close();
    };
}
