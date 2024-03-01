
// 退会モーダル
$(function(){
    // 退会ボタンをクリックしたときにモーダルを表示
    $('#withdrawButton').on('click', function(e){
        // フォームの送信を一時停止
        e.preventDefault();

        // モーダルを表示
        $('#modal').show();
    });

    // キャンセルボタンをクリックしたときにモーダルを非表示にする
    $('#cancel').on('click', function(){
        $('#modal').hide();
    });

});

// 画像プレビュー
$(function(){
    // ファイルが選択された時に処理を実行
    $('#avatar').on('change', function (e) {
        // ファイルリーダーの作成
        var reader = new FileReader();

        // ファイルが読み込まれた時に実行する関数を指定
        reader.onload = function (e) {
            // 読み込んだ画像ファイルをプレビューに設定
            $('#avatarPreview').attr('src', e.target.result).show();
        };

        // ファイルリーダーで画像ファイルをDataURLとして読み込む
        reader.readAsDataURL(e.target.files[0]);
    });
});


// ajaxで参考になったを切り替え
$(document).ready(function() {
    $('.js-helpful-button').click(function() {
        var button = $(this); // ボタン自体を変数に保持
        var reviewId = button.data('review-id');
        var userId = button.data('user-id');

        $.ajax({
            type: "POST",
            url: "toggleHelpful.php",
            data: { r_id: reviewId, u_id: userId },
            dataType: "json", // レスポンスをJSONとして扱う
            success: function(response) {
                // ここでアイコンをトグルする
                var icon = button.find('.c-icon'); // アイコン要素を取得
                icon.toggleClass('on');
                console.log('ajax処理（参考になったトグル）成功！');
            },
            error: function() {
                alert('エラーが発生しました。');
            }
        });
    });
});

// 参考になったアイコンの表示切り替え
$(function(){
   $('.js-helpful-button').click(function(){
       $(this).next.$('.js-toggle-icon-add').toggleClass('on');
       $(this).next.$('.js-toggle-icon-remove').toggleClass('on');
   })
});



