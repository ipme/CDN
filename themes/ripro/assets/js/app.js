
var body = jQuery('body');
var st = 0;
var lastSt = 0;
var navText = ['<i class="mdi mdi-chevron-left"></i>', '<i class="mdi mdi-chevron-right"></i>'];
var iconspin = '<i class="fa fa-spinner fa-spin"></i> ';
var iconcheck = '<i class="fa fa-check"></i> ';
var iconwarning = '<i class="fa fa-warning "></i> ';
var is_tencentcaptcha = false;

window.lazySizesConfig = window.lazySizesConfig || {};
window.lazySizesConfig.loadHidden = false;


jQuery(function() {
  'use strict';
  carousel();
  slider();
  megaMenu();
  categoryBoxes();
  picks();
  offCanvas();
  search();
  pagination();
  sidebar();
  fancybox();
  userinit();
  signup_popup();
  share_pop();
  widget_ri();
  notify();
  tap_full();
  ajax_searc();
});

jQuery(window).scroll(function() {
  'use strict';

  if (body.hasClass('navbar-sticky') || body.hasClass('navbar-sticky_transparent')) {
    window.requestAnimationFrame(navbar);
  }
});

jQuery(".header-dropdown").hover(function() {
  $(this).addClass('active');
}, function() {
  $(this).removeClass('active');
});

document.addEventListener('lazyloaded', function (e) {
  var options = {
    disableParallax: /iPad|iPhone|iPod|Android/,
    disableVideo: /iPad|iPhone|iPod|Android/,
    speed: 0.1,
  };
  
  if (
    jQuery(e.target).parents('.hero').length ||
    jQuery(e.target).hasClass('hero')
  ) {
    jQuery(e.target).jarallax(options);
  }

  if (
    (jQuery(e.target).parent().hasClass('module') && jQuery(e.target).parent().hasClass('parallax')) ||
    jQuery(e.target).parent().hasClass('entry-navigation')
  ) {
    jQuery(e.target).parent().jarallax(options);
  }
});


function open_signup_popup(){
  var el= $("#popup-signup")
  var popuphtml = el.html();
  Swal.fire({
    html: popuphtml,
    showConfirmButton: false,
    width: 340,
    padding: '0',
    onBeforeOpen: () => {
        el.empty();
    },
    onClose: () => {
        el.html(popuphtml);
    }
  })
}


function ajax_searc(){
  'use strict';
  $(".home_search_input").bind("input propertychange",function(event){
    var text = $(".home_search_input").val();
    if (text) {
      $.post(caozhuti.ajaxurl, {
        "action": "ajax_search",
        "text": text,
      }, function(result) {
        var resid = $(".home-search-results")
        if (result.length == 0) { 
              resid.empty().show().append('<li><strong>没有搜到相关内容，切换关键词试试。</strong></li>'); 
          } else { 
              resid.empty().show(); 
              for (var i = 0; i < result.length; i++) resid.append('<li><a class="focus" target="_blank" href="' + result[i]["url"] + '"><img class="" src="'+result[i]["img"]+'"></a><h2><a target="_blank" href="' + result[i]["url"] + '">' + result[i]["title"] + '</a></h2></li>');
          }
      });
    }
    $(document).click(function(event){
       var _con = $(".home-search-results");
       if(!_con.is(event.target) && _con.has(event.target).length === 0){
        _con.hide();
       }
    });
  });
}

function signup_popup(){
  'use strict';
  $(".login-btn").on("click", function(event) {
      event.preventDefault();
      open_signup_popup()
  });
  $(".must-log-in a").on("click", function(event) {
      event.preventDefault();
      open_signup_popup()
  });
  $(".comment-reply-login").on("click", function(event) {
      event.preventDefault();
      open_signup_popup()
  });
  $(document).on('click', ".nav-tabs a", function (event) {
    event.preventDefault()
    var _this = $(this)
    var toggle = _this.data("toggle")
    var _parent =_this.parent()
    var _tab_signup =$(".tab-content #signup")
    var _tab_login =$(".tab-content #login")

    _parent.addClass("active");
    _parent.siblings().removeClass("active");

    if (toggle == 'login') {
      _tab_login.addClass("active");
      _tab_login.siblings().removeClass("active");
    }
    if (toggle == 'signup') {
      _tab_signup.addClass("active");
      _tab_signup.siblings().removeClass("active");
    }
  })

  //登录处理
  $(document).on('click', ".go-login", function (event) {
    var _this = $(this)
    var deft = _this.text()
    _this.html(iconspin+deft)
    

    if (caozhuti.tencent_captcha.is == 1) {
      // 直接生成一个验证码对象
      var tx_captcha = new TencentCaptcha(caozhuti.tencent_captcha.appid, function (res) {
        console.log(res)
        if (res.ret === 0) {
          $.post(caozhuti.ajaxurl, {
              "action": "tencentcaptcha",
              "appid": caozhuti.tencent_captcha.appid,
              "Ticket": res.ticket,
              "Randstr": res.randstr
          }, function(data) {
              $.post(caozhuti.ajaxurl, {
                "action": "user_login",
                "username": $("input[name='username']").val(),
                "password": $("input[name='password']").val(),
                "rememberme": $("input[name='rememberme']").val()
              }, function(data) {
                  if (data.status == 1) {
                    _this.html(iconcheck+data.msg)
                    setTimeout(function(){
                        location.reload()
                    },1000)
                  }else{
                    _this.html(iconwarning+data.msg)
                    setTimeout(function(){
                        _this.html(deft)
                    },2000)
                  }
              });
          });
        }
      });
      // 显示验证码
      tx_captcha.show();
    }else{
      $.post(caozhuti.ajaxurl, {
        "action": "user_login",
        "username": $("input[name='username']").val(),
        "password": $("input[name='password']").val(),
        "rememberme": $("input[name='rememberme']").val()
      }, function(data) {
          if (data.status == 1) {
            _this.html(iconcheck+data.msg)
            setTimeout(function(){
                location.reload()
            },1000)
          }else{
            _this.html(iconwarning+data.msg)
            setTimeout(function(){
                _this.html(deft)
            },2000)
          }
      });
    }
    
  })

  //注册处理
  $(document).on('click', ".go-register", function (event) {
    var _this = $(this)
    var deft = _this.text()
    var user_name = $("input[name='user_name']").val();
    var user_email = $("input[name='user_email']").val();
    var user_pass = $("input[name='user_pass']").val();
    var user_pass2 = $("input[name='user_pass2']").val();
    var captcha = $("input[name='captcha']").val();
    _this.html(iconspin+deft)

    // 验证用户名
    if( !is_check_name(user_name) ){
      _this.html(iconwarning+'用户名格式错误')
      setTimeout(function(){_this.html(deft)},2000)
      return false;
    }
    //验证邮箱
    if( !is_check_mail(user_email) ){
      _this.html(iconwarning+'邮箱格式错误')
      setTimeout(function(){_this.html(deft)},2000)
      return false;
    }
    if(!is_check_pass(user_pass,user_pass2)){
      _this.html(iconwarning+'两次密码不一致')
      setTimeout(function(){_this.html(deft)},2000)
      return false;
    }
    if (caozhuti.tencent_captcha.is == 1) {
      // 直接生成一个验证码对象
      var tx_captcha = new TencentCaptcha(caozhuti.tencent_captcha.appid, function (ress) {
          console.log(ress.ret)
          if (ress.ret === 0) {
            // 验证OK
            $.post(caozhuti.ajaxurl, {
                "action": "user_register",
                "user_name": user_name,
                "user_email": user_email,
                "user_pass": user_pass,
                "captcha": captcha,
            }, function(data) {
                if (data.status == 1) {
                  _this.html(iconcheck+data.msg)
                  setTimeout(function(){location.reload()},1000)
                }else{
                  _this.html(iconwarning+data.msg)
                  setTimeout(function(){_this.html(deft)},2000)
                }
            });
          }
      });
      // 显示验证码
      tx_captcha.show();
    }else{
      // 验证OK
      $.post(caozhuti.ajaxurl, {
          "action": "user_register",
          "user_name": user_name,
          "user_email": user_email,
          "user_pass": user_pass,
          "captcha": captcha,
      }, function(data) {
          if (data.status == 1) {
            _this.html(iconcheck+data.msg)
            setTimeout(function(){location.reload()},1000)
          }else{
            _this.html(iconwarning+data.msg)
            setTimeout(function(){_this.html(deft)},2000)
          }
      });
    }

    
  })


}


function share_pop() {

  $('[etap="share"]').on('click', function(){
    // SHARE IMAGE
    if($('.article-content img:first').length ){
        var shareimage = $('.article-content img:first').attr('src')
    }

    // SHARE
    var share = {
        url: document.URL,
        pic: shareimage,
        title: document.title || '',
        desc: $('meta[name="description"]').length ? $('meta[name="description"]').attr('content') : ''    
    }
    var dom = $(this)
    var to = dom.data('share')
    var url = ''
    switch(to){
        case 'qq':
            url = 'http://connect.qq.com/widget/shareqq/index.html?url='+share.url+'&desc='+share.desc+'&summary='+share.title+'&site='+caozhuti.site_name+'&pics='+share.pic
            break;
        case 'weibo':
            url = 'http://service.weibo.com/share/share.php?title='+share.title+'&url='+share.url+'&source=bookmark&pic='+share.pic
            break;
    }

    if( !dom.attr('href') && !dom.attr('target') ){
      dom.attr('href', url).attr('target', '_blank')
    }
  })

  $('.btn-bigger-cover').on('click', function(event){
      event.preventDefault();
      var _this = $(this)
      var deft = _this.html()
      _this.html(iconspin)

      $.ajax({
        url: caozhuti.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: _this.data(),
        }).done(function (data) {
          if (data.s == 200) {
            Swal.fire({
              html: '<img class="swal2-image" src="'+data.src+'" alt="" style="display: flex;border-radius: 4px;box-shadow:0 34px 20px -24px rgba(0, 0, 0, 0.2);"><a href="'+data.src+'" download="海报" class="btn"><i class="fa fa-cloud-download"></i> 下载封面</a>',
              width: 350,
              showCancelButton: false,
              showConfirmButton:false,
              showCloseButton: true
            })
            _this.html(deft)
          } else {
            alert( data.m );
            _this.html(deft)
          }
      }).fail(function () {
          alert('Error：网络错误，请稍后再试！');
      })

  });

}


function userinit(){
  'use strict';

  //用户中心 修改个人信息
  $('[etap="submit_info"]').on('click', function(){
      var _this = $(this)
      var deft = _this.text()
      var email = $("input[name='email']").val();
      var nickname = $("input[name='nickname']").val();
      var user_avatar_type = $("input[name='user_avatar_type']:checked").val();
      var phone = $("input[name='phone']").val();
      var qq = $("input[name='qq']").val();
      var description = $("textarea[name='description']").val();
      var captcha = $("input[name='edit_email_cap']").val();
      _this.html(iconspin+deft)
      $.post(caozhuti.ajaxurl,
          {
              nickname: nickname,
              email: email,
              phone: phone,
              qq: qq,
              description: description,
              user_avatar_type: user_avatar_type,
              captcha: captcha,
              action: 'edit_user_info'
          },
          function (data) {
              if (data == '1') {
                _this.html(deft)
                Swal.fire({
                  type: 'success',
                  title: '修改成功',
                  showConfirmButton: false,
                  timer: 1500
                })
                setTimeout(function(){location.reload()},1000)
              }else{
                _this.html(deft)
                swal.fire({
                  type: 'error',
                  title: data
                }) 
              }
          }
      );
  });

  // 头像上传
  $("#addPic").change(function(e){
    var _this = $(this)
    var nonce = _this.data("nonce")
    var file = e.currentTarget.files[0];

    // console.log(file)

    //结合formData实现图片预览
    var sendData=new FormData();
    sendData.append('nonce',nonce);
    sendData.append('action','update_avatar_photo');
    sendData.append('file',file);

    const Toast = Swal.mixin({
        toast: true,
        showConfirmButton: false,
        timer: 3000
      });

    $.ajax({
      url: caozhuti.ajaxurl,
      type: 'POST',
      cache: false,
      data: sendData,
      processData: false,
      contentType: false
    }).done(function(res) {
      if (res.status == 1) {
        Toast.fire({
          type: 'success',
          title: res.msg
        })
        setTimeout(function(){location.reload()},1000)
      }else{
        Toast.fire({
          type: 'error',
          title: res.msg
        })
      }

    }).fail(function(res) {
      Toast.fire({
        type: 'error',
        title: '网络错误'
      })
    });

  });

  // 发送验证码 用户中心
  $(".edit_email_cap").on("click",function(){
      var _this = $(this)
      var deft = _this.text()
      var user_email = $("input[name='email']").val()
      _this.html(iconspin+deft)
      //验证邮箱
      if( !is_check_mail(user_email) ){
        Swal.fire({
            type: 'error',
            title:'邮箱格式错误'
          })
          return false;
      }
      $.post(caozhuti.ajaxurl, {
          "action": "captcha_email",
          "user_email": user_email
      }, function(data) {
          if (data.status == 1) {
            _this.html(deft)
            Swal.fire({
              type: 'success',
              title: data.msg,
              showConfirmButton: false,
              timer: 1500
            })
            // _this.html(data.msg)
            _this.attr("disabled","true");
          }else{
            _this.html(deft)
            Swal.fire({
              type: 'error',
              title: data.msg
            })
          }
      });
  });

  // 发送验证码 注册
  $(document).on('click', ".go-captcha_email", function (event) {
      var _this = $(this)
      var deft = _this.text()
      var user_email = $("input[name='user_email']").val()
      _this.html(iconspin+deft)
      _this.attr("disabled","true");
      //验证邮箱
      if( !is_check_mail(user_email) ){
          _this.html(iconwarning+'邮箱错误')
          setTimeout(function(){
            _this.html(deft)
            _this.removeAttr("disabled")
          },3000)
          return false;
      }
      $.post(caozhuti.ajaxurl, {
          "action": "captcha_email",
          "user_email": user_email
      }, function(data) {
          if (data.status == 1) {
            _this.html(iconcheck+'发送成功')
            setTimeout(function(){_this.html(deft)},3000)
          }else{
            _this.html(iconwarning+data.msg)
            setTimeout(function(){
              _this.html(deft)
              _this.removeAttr("disabled")
            },3000)
          }
      });
  });

  //解除绑定QQ unset_qq_open
  $('.unset-bind').on('click', function(){
      var _this = $(this)
      var deft = _this.text()
      var unsetid = $(this).data('id');
      var msg = "确定解绑？";
      _this.html(iconspin+deft)
      Swal.fire({
        title: msg,
        text: "解绑后需要重新绑定",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: '确定',
        cancelButtonText: '取消',
      }).then((result) => {
        if (result.value) {
          $.post(caozhuti.ajaxurl,{action: 'unset_open_oauth',unsetid: unsetid},
              function (data) {
                  if (data == 1) {
                    _this.html(deft)
                    Swal.fire('解绑成功','','success')
                    setTimeout(function(){location.reload()},1000)
                  }else{
                    _this.html(deft)
                    Swal.fire('解绑失败','','error')
                  }
              }
          );  
        }
      })

  });
  //vipbox
  $('.payvip-box .vip-info').on('click', function () {
      var _this = $(this); 
      var id = _this.data('id');
      _this.parents(".payvip-box").find('.vip-info').removeClass("active")
      _this.addClass('active');
      $("input[name='pay_id']").val(id);
      $('.go-payvip').removeAttr("disabled");
  });

  $('.go-payvip').on('click', function () {
      var _this = $(this); 
      var pay_id = $("input[name='pay_id']").val();
      var nonce = _this.data("nonce");
      var deft = _this.html()
      _this.html(iconspin+deft)
      $.post(caozhuti.ajaxurl,
          {
              nonce: nonce,
              pay_id: pay_id,
              action: 'pay_vip'
          },
          function (data) {
              if (data.status == '1') {
                _this.html(deft)
                Swal.fire('',data.msg,'success').then((result) => {if (result.value) {location.reload()}})
              }else{
                _this.html(deft)
                Swal.fire('',data.msg,'warning')
              }
          }
      );
      
  });

  // 输入金额充值
  $("#charge_num").bind("input propertychange",function(event){
     var rate = $('#rmbnum').data('rate');
     var inputnum = $("#charge_num").val();
     var cnynum = inputnum/rate;
     $('#rmbnum b').text(cnynum);
     if (cnynum > 0) {
          $('.go-charge').removeAttr("disabled")
     }else{
          $('.go-charge').attr("disabled","true");
     }
  });

  // 快速选择
  $('.amounts ul li').on('click', function () {
      var rate = $('#rmbnum').data('rate');
      $(this).find('p').addClass('selected');
      $(this).siblings($('.amounts ul li')).find('p').removeClass('selected');
      var cnynum = $(this).data('price')/rate;
      $("input[name='charge_num']").val($(this).data('price'));
      $('#rmbnum b').text(cnynum);
      $('.go-charge').removeAttr("disabled");
  });

  // 卡密使用按钮 go-cdk to cdk_pay
  $('.go-cdk').on('click', function () {
      var _this = $(this);
      var cdkcode = $("input[name='cdkcode']").val();
      var nonce = _this.data('nonce');
      var deft = _this.html()
      _this.html(iconspin+deft)
      Swal.fire({
        allowOutsideClick:false,
        width: 200,
        timer: 60000,
        onBeforeOpen: () => {
          Swal.showLoading()
          $.post(caozhuti.ajaxurl, {
              "action": "cdk_pay",
              "cdkcode": cdkcode,
              "nonce": nonce
          }, function(data) {
              if (data.status == 1) {
                _this.html(deft)
                Swal.fire('',data.msg,'success').then((result) => { if (result.value) { location.reload() } })
              }else{
                _this.html(deft)
                Swal.fire('',data.msg,'error')
              }
          });
        },
      })
      
  });

  // 卡密切换
  if (true) {}
  $("input[name='pay_type']").change(function(){
    var _this = $(this);
    var el_1 = $("#yuecz");
    var el_2 = $("#kamidiv");
    var pay_type = $("input[name='pay_type']:checked").val();
    if (pay_type == 3) {
      el_1.hide();
      el_2.show();
      $('.go-charge').attr("disabled","true");
    }else{
      el_2.hide();
      el_1.show();
      $('.go-charge').removeAttr("disabled");
    }
    
  });

  // 充值按钮 go-charge 
  $('.go-charge').on('click', function () {
      var _this = $(this);
      var charge_num = $("input[name='charge_num']").val();
      var pay_type = $("input[name='pay_type']:checked").val();
      var nonce = _this.data('nonce');
      var deft = _this.html()
      _this.html(iconspin+deft)

      Swal.fire({
        allowOutsideClick:false,
        width: 200,
        timer: 60000,
        onBeforeOpen: () => {
          Swal.showLoading()
          $.post(caozhuti.ajaxurl, {
              "action": "charge_pay",
              "charge_num": charge_num,
              "pay_type": pay_type,
              "nonce": nonce
          }, function(result) {
              // console.log(data)
              if (result.status == 1) {
                  if (result.type == 2) {
                      window.location.href=result.rurl
                  }else{
                    _this.html(deft)
                    Swal.fire({
                      html: result.msg,
                      showConfirmButton: false,
                      width: 300,
                      padding: '0',
                      background: 'rgb(224, 224, 224)',
                      allowOutsideClick:false,
                      showCloseButton: true,
                      animation: true
                    })
                    var checkOrder = setInterval(function() {
                        $.post(caozhuti.ajaxurl, {
                            "action": "check_pay",
                            "num": result.num,
                        }, function(data) {
                            if(data.status == 1){
                                clearInterval(checkOrder)
                                Swal.fire({
                                  type: 'success',
                                  title: data.msg,
                                  showConfirmButton: false,
                                  timer: 1500
                                }).then((result) => { if (result.value) { location.reload() } })
                            }
                        });
                    }, 3000);
                  }
              }else{
                  // 错误提示
                  _this.html(deft)
                  Swal.fire('',result.msg,'error')
              }
          });
        },
      })
      
  });

  //推广中心 复制按钮
  var btn = document.getElementById('refurl');
  if (btn) {
    var href = $('#refurl').data("clipboard-text");
    var clipboard = new ClipboardJS(btn);
    

    clipboard.on('success', function(e) {
      const Toast = Swal.mixin({
        toast: true,
        showConfirmButton: false,
        timer: 3000
      });
      Toast.fire({
        type: 'success',
        title: '复制成功：'+href
      })
    });
    clipboard.on('error', function(e) {
      const Toast = Swal.mixin({
        toast: true,
        showConfirmButton: false,
      });
      Toast.fire({
        type: 'error',
        title: '复制失败：'+href
      })
    });
  }

  //提现申请
  $('.go-add_reflog').on('click', function () {
      var _this = $(this);
      var deft = _this.html()
      var money = $("input[name='refmoney']").val();
      var qr_weixin = $("input[name='qr_weixin']").val();
      var qr_alipay = $("input[name='qr_alipay']").val();
      var max_money = _this.data("max");
      var nonce = _this.data('nonce');
      _this.html(iconspin+deft)
      if (!money) {
        _this.html(deft)
        Swal.fire('','请输入提现金额','warning')
        return false;
      }
      if (money > max_money) {
        _this.html(deft)
        Swal.fire('','可提现金额不足','warning')
        return false;
      }

      $.post(caozhuti.ajaxurl, {
          "action": "add_reflog",
          "money": money,
          "nonce": nonce
      }, function(data) {
          if (data.status == 1) {
              _this.html(deft)
              Swal.fire('',data.msg,'success').then((result) => { if (result.value) { location.reload() } })
          }else{
            _this.html(deft)
            Swal.fire('',data.msg,'warning')
          }
      });
  });

  //提现申请
  $('.go-add_reflog2').on('click', function () {
      var _this = $(this);
      var deft = _this.html()
      var money = $("input[name='refmoney']").val();
      var qr_weixin = $("input[name='qr_weixin']").val();
      var qr_alipay = $("input[name='qr_alipay']").val();
      var max_money = _this.data("max");
      var nonce = _this.data('nonce');
      _this.html(iconspin+deft)
      if (!money) {
        _this.html(deft)
        Swal.fire('','请输入提现金额','warning')
        return false;
      }
      if (money > max_money) {
        _this.html(deft)
        Swal.fire('','可提现金额不足','warning')
        return false;
      }

      $.post(caozhuti.ajaxurl, {
          "action": "add_reflog2",
          "money": money,
          "nonce": nonce
      }, function(data) {
          if (data.status == 1) {
              _this.html(deft)
              Swal.fire('',data.msg,'success').then((result) => { if (result.value) { location.reload() } })
          }else{
            _this.html(deft)
            Swal.fire('',data.msg,'warning')
          }
      });
  });

  //修改收款码
  $('[etap="submit_qr"]').on('click', function(){
      var _this = $(this)
      var deft = _this.html()
      var qr_alipay = $("input[name='qr_alipay']").val();
      var qr_weixin = $("input[name='qr_weixin']").val();
      // var fd_qr_weixin = qr_weixin.indexOf("wxp://");
      // var fd_qr_alipay = qr_alipay.indexOf("https://qr.alipay.com/");
      _this.html(iconspin+deft)
      if (qr_alipay == '') {
        _this.html(deft)
        Swal.fire('','支付宝收款码不正确','warning')
        return false;
      }
      if (qr_weixin == '') {
        _this.html(deft)
        Swal.fire('','微信收款码不正确','warning')
        return false;
      }
      $.post(caozhuti.ajaxurl,
          {
              qr_alipay: qr_alipay,
              qr_weixin: qr_weixin,
              action: 'edit_user_qr'
          },
          function (data) {
              if (data == '1') {
                _this.html(deft)
                Swal.fire('','保存成功','success').then((result) => {if (result.value) {location.reload()}})
              }else{
                _this.html(deft)
                Swal.fire('','上传失败','error')
              }
          }
      );
  });

  //修改密码
  $('.go-repassword').on('click', function(event){
    event.preventDefault()
    var _this = $(this)
    var deft = _this.html()
    var password = $("input[name='password']").val();
    var new_password = $("input[name='new_password']").val();
    var re_password = $("input[name='re_password']").val();
    _this.html(iconspin+deft)
    if (!(password && new_password && re_password)) {
      _this.html(deft)
      Swal.fire('','请输入完整密码','warning')
      return false;
    }
    if (new_password != re_password) {
      _this.html(deft)
      Swal.fire('','两次输入新密码不一致','warning')
      return false;
    }
    
    $.post(caozhuti.ajaxurl,
        {
          password: password,
          new_password: new_password,
          re_password: re_password,
          action: 'edit_repassword'
        },
        function (data) {
          if (data == '1') {
            _this.html(deft)
            Swal.fire('','修改成功','success').then((result) => {if (result.value) {location.reload()}})
          }else{
            _this.html(deft)
            Swal.fire('',data,'error')
          }
        }
    );

  });


  // 收藏文章 
  $(document).on('click', '[etap="star"]', function (event) {
      event.preventDefault()
      var _this = $(this)
      var deft = _this.html()
      var post_id = _this.data('postid');
      _this.html(iconspin)
      $.post(caozhuti.ajaxurl, {
          "action": "fav_post",
          "post_id": post_id
      }, function(data) {
          if (data.status == 1) {
            _this.html(deft)
            const Toast = Swal.mixin({
              toast: true,
              showConfirmButton: false,
              timer: 1000
            });
            Toast.fire({
              type: 'success',
              title: data.msg
            })

            // Swal.fire({
            //   type: 'success',
            //   title: data.msg,
            //   showConfirmButton: false,
            //   timer: 1500
            // })
            // _this.html(data.msg)
            _this.attr("disabled","true");
            _this.toggleClass("ok");
          }else{
            _this.html(deft)
            const Toast = Swal.mixin({
              toast: true,
              showConfirmButton: false,
              timer: 2000
            });
            Toast.fire({
              type: 'error',
              title: data.msg
            })

            // Swal.fire({
            //   type: 'error',
            //   title: data.msg
            // })
          }
      });
  });


  //签到 unset_qq_open
  $('.click-qiandao').on('click', function(){
    var _this = $(this)
    var deft = _this.html()
    _this.html(iconspin+'签到中...')
    $.post(caozhuti.ajaxurl,{action: 'user_qiandao'},
          function (data) {
              if (data.status == 1) {
                _this.html(deft)
                Swal.fire(data.msg,'','success')
                setTimeout(function(){location.reload()},1000)
              }else{
                _this.html(deft)
                Swal.fire(data.msg,'','warning')
              }
          }
      );  
  });


  if (true) {
    var clipboard = new ClipboardJS('.cop-codecdk');
    clipboard.on('success', function(e) {
      const Toast = Swal.mixin({
        toast: true,
        showConfirmButton: false,
        timer: 3000
      });
      Toast.fire({
        type: 'success',
        title: '卡密复制成功'
      })
    });
    clipboard.on('error', function(e) {
      const Toast = Swal.mixin({
        toast: true,
        showConfirmButton: false,
      });
      Toast.fire({
        type: 'error',
        title: '复制失败'
      })
    });
  }


  //投稿 go-write_post write_post
  if (document.getElementById("editor")) {
    $("select[name='cao_status']").change(function(){
        var cao_status = $(this).val();
        if (cao_status == 'free') {
            $(".hide1,.hide2,.hide3,.hide4,.hide5").hide()
        }
        if (cao_status == 'fee') {
            $(".hide5").hide()
            $(".hide1,.hide2,.hide3,.hide4").show()
        }
        if (cao_status == 'hide') {
            $(".hide3,.hide4").hide()
            $(".hide1,.hide2,.hide5").show()
        }
    })

    //插入编辑器
    var E = window.wangEditor
    var editor = new E('#editor')
    editor.customConfig.uploadImgServer = caozhuti.ajaxurl
    // 将图片大小限制为 2M
    editor.customConfig.uploadImgMaxSize = 2 * 1024 * 1024
    editor.customConfig.uploadImgParams = {
        nonce: $(".go-write_post").data("nonce"),
        action: 'update_img'
    }
    editor.customConfig.uploadFileName = 'file'
    editor.create()

  }

  

  $('.go-write_post').on('click', function(event){
    event.preventDefault()
    var _this = $(this)
    var deft = _this.html()

    var post_title = $("input[name='post_title']").val();
    var tinymce = $("#post_content_ifr").contents().find("#tinymce"); 
    var post_content = editor.txt.html();
    var post_cat = $("select[name='post_cat']").val();
    var cao_status = $("select[name='cao_status']").val();
    var cao_price = $("input[name='cao_price']").val();
    var post_excerpt = $("textarea[name='post_excerpt']").val();
    var cao_vip_rate = $("input[name='cao_vip_rate']").val();
    var cao_pwd = $("input[name='cao_pwd']").val();
    var cao_downurl = $("input[name='cao_downurl']").val();
    var post_status = _this.data("status");
    var edit_id = _this.data("edit_id") ? _this.data("edit_id") : 0;
    var nonce = _this.data("nonce");

    _this.html(iconspin+deft)
    if (post_title.length < 6) {
      _this.html(deft)
      Swal.fire('','标题最低6个字符','warning')
      return false;
    }
    if (post_content == '') {
      _this.html(deft)
      Swal.fire('','请输入文章内容','warning')
      return false;
    }
    if (cao_status != 'free') {
        if (cao_price <= 0) {
          _this.html(deft)
          Swal.fire('','请输入正确价格（整数）','warning')
          return false;
        }
        if ((cao_vip_rate>1) || (cao_vip_rate < 0)) {
          _this.html(deft)
          Swal.fire('','折扣区间必须是：0.1~1','warning')
          return false;
        }
    }

    if (cao_status == 'fee') {
        if (cao_downurl.length < 5) {
          _this.html(deft)
          Swal.fire('','请输入下载地址','warning')
          return false;
        }
    }
    console.log(post_excerpt)
    
    
    
    $.post(caozhuti.ajaxurl,
        {
          post_title: post_title,
          post_content: post_content,
          post_excerpt: post_excerpt,
          post_cat: post_cat,
          cao_status: cao_status,
          cao_price: cao_price,
          cao_vip_rate: cao_vip_rate,
          cao_pwd: cao_pwd,
          cao_downurl: cao_downurl,
          post_status: post_status,
          edit_id: edit_id,
          action: 'cao_write_post'
        },
        function (data) {
          if (data.status == 1) {
            _this.html(deft)
            Swal.fire({
              html: data.msg,
              type: 'success',
            }).then((result) => {
              if (result.value) {
                location.reload()
              }
            })
          }else{
            _this.html(deft)
            Swal.fire('',data.msg,'warning')
          }
        }
    );

  });


}
// end function


function to_pay_post(pay_type,order_type,post_id,nonce){

  Swal.fire({
    allowOutsideClick:false,
    width: 200,
    timer: 60000,
    onBeforeOpen: () => {
      Swal.showLoading()
      $.post(caozhuti.ajaxurl, {
          "action": "go_post_pay",
          "post_id": post_id,
          "pay_type": pay_type,
          "order_type": order_type,
          "nonce": nonce
      }, function(result) {
          // console.log(data)
          if (result.status == 1) {
              if (result.type == 2) {
                  window.location.href=result.rurl;
                 
              }else{
                Swal.fire({
                  html: result.msg,
                  showConfirmButton: false,
                  width: 300,
                  padding: '0',
                  background: 'rgb(224, 224, 224)',
                  allowOutsideClick:false,
                  showCloseButton: true,
                  animation: true,
                  onClose: () => {
                    location.reload()
                  }
                })
                // https://vip.ylit.cc/
                var checkOrder = setInterval(function() {
                    $.post(caozhuti.ajaxurl, {
                        "action": "check_pay",
                        "num": result.num,
                        "post_id": post_id,
                    }, function(data) {
                        if(data.status == 1){
                            clearInterval(checkOrder)
                            Swal.fire({
                              type: 'success',
                              title: data.msg,
                              showConfirmButton: false,
                              timer: 1500,
                              onClose: () => {
                                location.reload()
                              }
                            })
                        }
                    });
                }, 3000);
              }
          }else{
              // 错误提示
              Swal.fire('',result.msg,'error')
          }
      });
    },
  })

}
// 文章购买函数
function to_yecpay_post(post_id,nonce,price){
    var _this = $(this)
    var post_id = post_id
    var nonce = nonce
    var price = price
    Swal.fire({
      // title: '购买确认',
      text: "购买此资源将消耗【"+price+"】",
      type: 'question',
      showCancelButton: true,
      confirmButtonText: '购买',
      cancelButtonText: '取消',
      reverseButtons: true
      
    }).then((result) => {
      if (result.value) {
        // 开始请求
        Swal.fire({
          allowOutsideClick:false,
          width: 200,
          timer: 60000,
          onBeforeOpen: () => {
            Swal.showLoading()
            $.post(caozhuti.ajaxurl, {
                "action": "add_pay_post",
                "post_id": post_id,
                "nonce": nonce
            }, function(data) {
                if (data.status == 1) {
                  Swal.fire({
                    title: data.msg,
                    type: 'success',
                  }).then((result) => {
                    if (result.value) {
                      location.reload()
                    }
                  })
                }else{
                  Swal.fire({
                    type: 'warning',
                    html: data.msg,
                  })
                  // Swal.fire('',data.msg,'error')
                }
            });
          },
        })
        
      }
    })
};

// widget
function widget_ri(){
  'use strict';
  // 文章购买按钮 go-pay
  

  //文章购买NEW 支付模式
  $(".click-pay").on("click",function(){
    var _this = $(this)
    var deft = _this.html()
    var post_id = $(this).data("postid")
    var nonce = $(this).data("nonce")
    var price = $(this).data("price")
    // _this.html(iconspin+deft)

    Swal.fire({
      title: '请选择支付方式',
      html:caozhuti.pay_type_html.html,
      showConfirmButton: false,
      width: 300,
      showCloseButton: true,
      onBeforeOpen: () => {
        const content = Swal.getContent()
        const $ = content.querySelector.bind(content)
        const alipay = $('#alipay') //支付宝
        const weixinpay = $('#weixinpay') //微信支付
        const yecpay = $('#yecpay') //余额支付
        
        if (alipay) {
          alipay.addEventListener('click', () => {
            to_pay_post(caozhuti.pay_type_html.alipay,'other',post_id,nonce)
          })
        }
        if (weixinpay) {
          weixinpay.addEventListener('click', () => {
            to_pay_post(caozhuti.pay_type_html.weixinpay,'other',post_id,nonce)
          })
        }
        if (yecpay) {
          yecpay.addEventListener('click', () => {
            to_yecpay_post(post_id,nonce,price)
          })
        }

      },
      onClose: () => {
        // _this.html(deft)
      }
    })
    return;
  });


  //下载文件
  $(".go-down").one("click",function(){
    var _this = $(this);
    var deft = _this.html()
    var deftext = _this.text()
    var post_id = _this.data("id")
    

    //tanchuang
    _this.html(iconspin+deftext)

    Swal.fire({
      allowOutsideClick:false,
      width: 200,
      timer: 60000,
      onBeforeOpen: () => {
        Swal.showLoading()
        $.post(caozhuti.ajaxurl, {
            "action": "user_down_ajax",
            "post_id": post_id
        }, function(data) {
            if (data.status == 1) {
              _this.html(deft)
              Swal.fire({
                title: '下载地址获取成功',
                // text: "点击立即下载跳转到下载页面",
                type: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '立即下载',
                cancelButtonText: '关闭',
              }).then((result) => {
                if (result.value) {
                  window.open(data.msg)
                  location.reload()
                }else{
                  location.reload()
                }
              })
            }else{
              _this.html(deft)
              Swal.fire({
                type: 'warning',
                html: data.msg,
              }).then((result) => {
                location.reload()
              })
            }
        });
      },
    })

      
    
  });


}
// end function

function navbar() {
  'use strict';

  st = jQuery(window).scrollTop();
  var adHeight = jQuery('.ads.before_header').outerHeight();
  var navbarHeight = jQuery('.site-header').height();
  var stickyTransparent = jQuery('.navbar-sticky_transparent.with-hero');
  var adsBeforeHeader = jQuery('.navbar-sticky.ads-before-header, .navbar-sticky_transparent.ads-before-header');
  var stickyStickyTransparent = jQuery('.navbar-sticky.navbar-slide, .navbar-sticky_transparent.navbar-slide');

  if (st > (navbarHeight + adHeight)) {
    stickyTransparent.addClass('navbar-sticky');
  } else {
    stickyTransparent.removeClass('navbar-sticky');
  }

  if (st > adHeight) {
    adsBeforeHeader.addClass('stick-now');
  } else {
    adsBeforeHeader.removeClass('stick-now');
  }

  if (st > lastSt && st > (navbarHeight + adHeight + 100)) {
    stickyStickyTransparent.addClass('slide-now');
  } else {
    if (st + jQuery(window).height() < jQuery(document).height()) {
      stickyStickyTransparent.removeClass('slide-now');
    }
  }

  lastSt = st;
}

function carousel() {
  'use strict';

  jQuery('.carousel.module').owlCarousel({
    autoHeight: true,
    dots: false,
    margin: 30,
    nav: true,
    navSpeed: 500,
    navText: navText,
    responsive: {
      0: {
        items: 1,
      },
      768: {
        items: 3,
      },
      992: {
        items: 4,
      },
    },
  });
}

function slider() {
  'use strict';

  var autoplayOptions = {
    autoplay: true,
    autoplaySpeed: 800,
    loop: true,
  };

  var bigSlider = jQuery('.slider.big.module');
  var bigSliderOptions = {
    animateOut: 'fadeOut',
    dots: true,
    items: 1,
    nav: false,
    navText: navText,
  };
  bigSlider.each(function(i, v) {
    if (jQuery(v).hasClass('autoplay')) {
      var bigSliderAuto = Object.assign(autoplayOptions, bigSliderOptions);
      jQuery(v).owlCarousel(bigSliderAuto);
    } else {
      jQuery(v).owlCarousel(bigSliderOptions);
    }
  });

  var centerSlider = jQuery('.slider.center.module');
  var centerSliderOptions = {
    center: true,
    dotsSpeed: 800,
    loop: true,
    margin: 20,
    responsive: {
      0: {
        items: 1,
      },
      768: {
        items: 2,
      },
    },
  };
  centerSlider.each(function(i, v) {
    if (jQuery(v).hasClass('autoplay')) {
      var centerSliderAuto = Object.assign(autoplayOptions, centerSliderOptions);
      jQuery(v).owlCarousel(centerSliderAuto);
    } else {
      jQuery(v).owlCarousel(centerSliderOptions);
    }
  });

  var thumbnailSlider = jQuery('.slider.thumbnail.module');
  var thumbnailSliderOptions = {
    dotsData: true,
    dotsSpeed: 800,
    items: 1,
  };
  thumbnailSlider.each(function(i, v) {
    if (jQuery(v).hasClass('autoplay')) {
      var thumbnailSliderAuto = Object.assign(autoplayOptions, thumbnailSliderOptions);
      jQuery(v).owlCarousel(thumbnailSliderAuto);
    } else {
      jQuery(v).owlCarousel(thumbnailSliderOptions);
    }
  });
}

function tap_full(){
  'use strict';
  $('[etap="to_full"]').on('click', function(){
    var element = document.documentElement;     // 返回 html dom 中的root 节点 <html>
    
    if(!$('body').hasClass('full-screen')) {
        $('body').addClass('full-screen');
        $('#alarm-fullscreen-toggler').addClass('active');
        // 判断浏览器设备类型
        if(element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen){   // 兼容火狐
            element.mozRequestFullScreen();
        } else if(element.webkitRequestFullscreen) {    // 兼容谷歌
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {   // 兼容IE
            element.msRequestFullscreen();
        }
    } else {
        console.log(document);
        $('body').removeClass('full-screen');
        $('#alarm-fullscreen-toggler').removeClass('active');
        //  退出全屏
        if(document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
  })
}

function megaMenu() {
  'use strict';

  var options = {
    items: 5,
    margin: 15,
  };

  jQuery('.menu-posts').not('.owl-loaded').owlCarousel(options);
  var scroller = $('.rollbar')

  $(window).scroll(function() {
      var h = document.documentElement.scrollTop + document.body.scrollTop
      h > 200 ? scroller.fadeIn() : scroller.fadeOut();
  })
  $('[etap="to_top"]').on('click', function(){
    $('html,body').animate({
            scrollTop: 0
        }, 300)
  })

  //tap_dark
  $(document).on('click', ".tap-dark", function (event) {
    var _this = $(this)
    var deft = _this.html()
    _this.html(iconspin)
   

    $.ajax({
        url: caozhuti.ajaxurl,
        type: 'POST',
        dataType: 'html',
        data: {
            "is_ripro_dark": $('body').hasClass('ripro-dark') === true ? '0' : '1',
            action: 'tap_dark'
        },
    })
    .done(function(response) {
        toggleDarkMode()
        _this.html(deft)
    })


  })

}


function toggleDarkMode() {
    $('body').toggleClass('ripro-dark')
}


function categoryBoxes() {
  'use strict';

  jQuery('.category-boxes').owlCarousel({
    dots: false,
    margin: 30,
    nav: true,
    navSpeed: 500,
    navText: navText,
    responsive: {
      0: {
        items: 1,
      },
      768: {
        items: 2,
      },
      992: {
        items: 3,
      },
      1230: {
        items: 4,
      },
    },
  });
}

function picks() {
  'use strict';

  jQuery('.picked-posts').not('.owl-loaded').owlCarousel({
    autoHeight: true,
    autoplay: true,
    autoplayHoverPause: true,
    autoplaySpeed: 500,
    autoplayTimeout: 3000,
    items: 1,
    loop: true,
  });
}

function offCanvas() {
  'use strict';

  var burger = jQuery('.burger');
  var canvasClose = jQuery('.canvas-close');

  jQuery('.main-menu .nav-list').slicknav({
    label: '',
    prependTo: '.mobile-menu',
  });

  burger.on('click', function() {
    body.toggleClass('canvas-opened');
    body.addClass('canvas-visible');
    dimmer('open', 'medium');
  });

  canvasClose.on('click', function() {
    if (body.hasClass('canvas-opened')) {
      body.removeClass('canvas-opened');
      dimmer('close', 'medium');
    }
  });

  jQuery('.dimmer').on('click', function() {
    if (body.hasClass('canvas-opened')) {
      body.removeClass('canvas-opened');
      dimmer('close', 'medium');
    }
  });

  jQuery(document).keyup(function(e) {
    if (e.keyCode == 27 && body.hasClass('canvas-opened')) {
      body.removeClass('canvas-opened');
      dimmer('close', 'medium');
    }
  });
}

function search() {
  'use strict';

  var searchContainer = jQuery('.main-search');
  var searchField = searchContainer.find('.search-field');

  jQuery('.search-open').on('click', function() {
    body.addClass('search-open');
    searchField.focus();
  });

  jQuery(document).keyup(function(e) {
    if (e.keyCode == 27 && body.hasClass('search-open')) {
      body.removeClass('search-open');
    }
  });

  jQuery('.search-close').on('click', function() {
    if (body.hasClass('search-open')) {
      body.removeClass('search-open');
    }
  });

  jQuery(document).mouseup(function(e) {
    if (!searchContainer.is(e.target) && searchContainer.has(e.target).length === 0 && body.hasClass('search-open')) {
      body.removeClass('search-open');
    }
  });
}



function pagination() {
  'use strict';

  var wrapper = jQuery('.posts-wrapper');
  var button = jQuery('.infinite-scroll-button');
  var options = {
    append: wrapper.selector + ' > *',
    debug: false,
    hideNav: '.posts-navigation',
    history: false,
    path: '.posts-navigation .nav-previous a',
    prefill: true,
    status: '.infinite-scroll-status',
  };

  if (body.hasClass('pagination-infinite_button')) {
    options.button = button.selector;
    options.prefill = false;
    options.scrollThreshold = false;

    wrapper.on('request.infiniteScroll', function (event, path) {
      button.html(caozhuti.infinite_loading);
    });

    wrapper.on('load.infiniteScroll', function (event, response, path) {
      button.html(caozhuti.infinite_load);
    });
  }

  if ((body.hasClass('pagination-infinite_button') || body.hasClass('pagination-infinite_scroll')) && body.hasClass('paged-next')) {
    wrapper.infiniteScroll(options);
  }
}

function sidebar() {
  'use strict';
    var navbarHeight = jQuery('.site-header').height();
    var topHeight = 0;
    // 移动端自动将下载信息放文章末尾
    var this_max_width = $(window).width();
    if (this_max_width < 768 ) {
      $("aside .widget.widget-pay").insertAfter($("#pay-single-box"));
    }else{
      jQuery('.container .sidebar-column').theiaStickySidebar({
        additionalMarginTop: navbarHeight+topHeight
      });
      jQuery('.container .content-column').theiaStickySidebar({
        additionalMarginTop: navbarHeight+topHeight
      });
    }
}

function fancybox() {
  'use strict';
  $(function() {
    if (caozhuti.is_singular == 0) {return false;}
    $('.entry-content a[href*=".jpg"],.entry-content a[href*=".jpeg"],.entry-content a[href*=".png"],.entry-content a[href*=".gif"]').each(function() {
        if ($(this).parents('a').length == 0) {
            $(this).attr("data-fancybox","images")
        }
    });
  });
}

function dimmer(action, speed) {
  'use strict';

  var dimmer = jQuery('.dimmer');

  switch (action) {
    case 'open':
      dimmer.fadeIn(speed);
      break;
    case 'close':
      dimmer.fadeOut(speed);
      break;
  }
}

//公告弹窗
function notify(){
  'use strict';
  $(function() {
    if (caozhuti.site_notice.is == 0) {return false;}
    if($.cookie("cao_notice_cookie")==null){
      Swal.fire({
        html: caozhuti.site_notice.html,
        background: caozhuti.site_notice.color,
        showConfirmButton: false,
        width: 560,
        padding: '0',
        allowOutsideClick:false,
        showCloseButton: true,
      }).then((result) => {$.cookie('cao_notice_cookie', '1', { expires: 1 })})
    }
  });
}

//广告弹窗
function ad_popup(url, title, w, h) {
  'use strict';

  title = title || '';
  w = w || 500;
  h = h || 300;

  var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
  var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

  var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
  var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

  var left = ((width / 2) - (w / 2)) + dualScreenLeft;
  var top = ((height / 2) - (h / 2)) + dualScreenTop;
  var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

  if (window.focus) {
    newWindow.focus();
  }
}

function is_check_name(str) {    
    return /^[\w]{3,16}$/.test(str) 
}
function is_check_mail(str) {
    return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(str)
}
function is_check_pass(str1,str2) {
    if (str1.length < 6) {
        return false;
    }
    if (str1 =! str2) {
       return false; 
    }
    return true;
}