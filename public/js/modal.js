/**
 * モーダルを開く
 * @param {string} id
 */
const openModal = (id) => {
    $("#" + id).addClass("show");
    $("body").addClass("open-modal");
};

/**
 * モーダルを閉じる
 * @param {string} id
 */
const closeModal = (id) => {
    $("#" + id).removeClass("show");
    $("body").removeClass("open-modal");
};

$(function () {
    $(".modal-layout").on("click", function (e) {
        if (e.target !== e.currentTarget) return;
        closeModal($(this).parent()[0].id);
    });
});
