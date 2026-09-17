export function showConfirmDialog(event, dialogId) {
    event.preventDefault();

    const dialog = document.getElementById(dialogId);
    const btnConfirm = document.querySelector(`#${dialogId} [data-action="confirm"]`);
    const btnCancel = document.querySelector(`#${dialogId} [data-action="cancel"]`);

    dialog.showModal();

    btnConfirm.onclick = () => {
        event.target.submit();
        dialog.close();
    };

    btnCancel.onclick = () => {
        dialog.close();
    };
}
