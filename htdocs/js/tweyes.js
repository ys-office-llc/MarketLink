$(document).ready(function() {

  $('#dt').DataTable({
    // 日本語化
    'language': {
      'url': '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Japanese.json'
    },
    // 一覧の表示件数を設定する
    'lengthMenu': [
      [100, 50, 25, 10, -1],
      [100, 50, 25, 10, 'すべて']
    ],
    // ソートや表示件数などの状態を保存する
    'stateSave': true,
    // 横スクロールバーを有効にする (scrollXはtrueかfalseで有効無効を切り替えます)
    scrollX: false,
    // 縦スクロールバーを有効にする (scrollYは200, "200px"など「最大の高さ」を指定します)
    //scrollY: 800px
  });

  $('.confirm').click(function() {
    if (!confirm('実行しますか？')) {

      return false;
    }
  });

  $('#confirm_delete').click(function() {

    if (!confirm('本当に削除しますか？')) {

      return false;
    }
  });

  $('#confirm_migration_plans').click(function() {

    if (!confirm('プラン切り替え後は一か月間は変更できません。続行しますか？')) {

      return false;
    }
  });

  $('#confirm_migration_packages').click(function() {

    if (!confirm('パッケージ切り替え後は一か月間は変更できません。続行しますか？')) {

      return false;
    }
  });
  $("#input-ja").fileinput({
    language: 'ja',
    //uploadUrl: "/file-upload-batch/2",
    allowedFileExtensions: ['jpg', 'png', 'gif']
  });

  $('#preview_textarea').keyup(function(){
    $('#preview_div').html(
      $('#preview_textarea').val()
    );
  });

  $('#preview_textarea').click(function(){
    $('#preview_div').html(
      $('#preview_textarea').val()
    );
  });

  $('#preview_textarea').keyup(function(){
    $('#preview_div').html(
      $('#preview_textarea').val()
    );
  });

  $('#yahoo_auctions_textarea').click(function(){

    $('#yahoo_auctions_htmlarea').html(
      $('#yahoo_auctions_textarea').val()
    );

  });

  $('#ebay_us_textarea').click(function(){

    $('#ebay_us_htmlarea').html(
      $('#ebay_us_textarea').val()
    );

  });

  $('#check-all').on('click', function() {

    $('input.bar:checkbox').prop('checked', $(this).is(':checked'));  

  });

});
