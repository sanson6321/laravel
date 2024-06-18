/**
 * モーダルを開く
 * @param {string} id
 */
const openModal = (id) => {
    $("#" + id).addClass("show");
    if ($("body").height() > $(window).height()) {
        $("body").addClass("open-modal");
    }
};

/**
 * モーダルを閉じる
 */
const closeModal = () => {
    $(".modal.show").removeClass("show");
    $("body").removeClass("open-modal");
};

$(function () {
    var target = null;
    $(".modal-layout").on("mousedown", function (e) {
        target = e.target;
    });
    $(".modal-layout").on("mouseup", function (e) {
        if (e.target !== target) return;
        if (e.target !== e.currentTarget) return;
        closeModal();
    });
    $(".modal-close").on("click", function () {
        closeModal();
    });
});
