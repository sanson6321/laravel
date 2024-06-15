/**
 * ajax共通
 * @param {string} url
 * @param {json} data
 */
var ajaxAction = function (url, data) {
    var deferred = new $.Deferred();
    $.ajax(url, {
        type: "post",
        data: data,
        dataType: "json",
        cache: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            clearFormError();
            messageError("");
        },
    })
        .then(
            // 成功処理
            function (data) {
                deferred.resolve(data);
            },
            // 失敗処理
            function (data) {
                if (!data.hasOwnProperty("responseJSON")) {
                    // 予期せぬエラー
                    messageError("Server Error");
                    return;
                }
                if (data.responseJSON.hasOwnProperty("errors")) {
                    // バリデーションエラー
                    const feedback =
                        '<label class="feedback">__error__</label>';
                    $.each(data.responseJSON.errors, function (key, error) {
                        let name = "";
                        const keyList = key.split(".");
                        $.each(keyList, function (index, value) {
                            name += index === 0 ? value : "[" + value + "]";
                        });
                        let form = $("#form-ajax").find(
                            "[name='" + name + "']"
                        );
                        form.addClass("form-error");
                        form.after(feedback.replace(/__error__/g, error));
                    });
                    messageError("入力を確認してください");
                    return;
                }
                if (data.responseJSON.hasOwnProperty("message")) {
                    // システムエラー
                    messageError(data.responseJSON.message);
                }
                if (data.responseJSON.hasOwnProperty("error")) {
                    // システムエラー
                    messageError(data.responseJSON.error);
                }
                deferred.reject(data.responseJSON);
            }
        )
        .catch(function (data) {
            console.error(data);
            alert("Server Error");
        });
    return deferred;
};

/**
 * ajax表示用
 * @param {string} url
 * @param {json} data
 */
var ajaxRender = (url, data) => {
    var deferred = new $.Deferred();
    $.ajax(url, {
        type: "post",
        data: data,
        dataType: "html",
        cache: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    })
        .then(
            // 成功処理
            function (data) {
                deferred.resolve(data);
            },
            // 失敗処理
            function (data) {
                if (data.responseJSON.hasOwnProperty("message")) {
                    // システムエラー
                    messageError(data.responseJSON.message);
                }
                if (data.responseJSON.hasOwnProperty("error")) {
                    // システムエラー
                    messageError(data.responseJSON.error);
                }
                deferred.reject(data.responseJSON);
            }
        )
        .catch(function (data) {
            console.error(data);
            alert("Server Error");
        });
    return deferred;
};

/**
 * エラーフォーム初期化
 */
var clearFormError = function () {
    $(".form-error").removeClass("form-error");
    $(".feedback").remove();
};

/**
 * エラーメッセージ表示
 */
var messageError = function (message) {
    if (message === "") {
        $(".toast-ajax").hide().css("visibility", "hidden");
        $("#message-error").text("");
        return;
    }
    $(".toast-ajax").show().css("visibility", "visible");
    $("#message-error").text(message);
    setTimeout(function () {
        $(".toast-ajax").hide().css("visibility", "hidden");
    }, 5000);
    return;
};
