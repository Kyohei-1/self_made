$(function(){
  //footerの最下部固定
  var $ftr = $('#footer');


  if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
    $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
  }

  var app = new Vue({
      el: '#changeImg',
      methods: {
        changeImg: function(path){
          // console.log(path);
          // clickされた場所が左端ならば、サンプル画像を入れる
          if(path === 'http://placehold.jp/300x300.png'){
            $('#image-main').attr('src','http://placehold.jp/300x300.png');
            console.warn('初期値に戻る');
            return 0;
          }
          // mainのDOMを取ってくる
          var $data = document.getElementById('image-main');
          // console.log($data);
          //attrで書き換え
          $('#image-main').attr("src",path);
        }
      }
    });





});
