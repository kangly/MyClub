/**
 * Created by kangly on 2017/11/20.
 */
$(function(){
    var current_url = location.href;
    $('#sidebar-nav #dashboard-menu li a').each(function(){
        var menu_url = $(this).attr('href');
        if(current_url.indexOf(menu_url)!=-1){
            var act_menu = $(this).parent();
            if (act_menu.parent().hasClass('submenu')){
                act_menu.parent().parent().addClass('active');
                act_menu.parent().addClass('active');
                $(this).addClass('current_active');
            }else{
                act_menu.addClass('active');
            }
        }
    });

    $('#sel-active-nav li a').each(function(){
        var menu_url = $(this).attr('href');
        if(current_url.indexOf(menu_url)!=-1){
            $(this).parent().parent().find('li').removeClass('active');
            $(this).parent().addClass('active');
        }
    });
});

layer.config({
    skin: 'demo-class', //layer默认皮肤(全局),
    shadeClose: true //点击弹层外区域关闭弹层
});

/**
 * 加载bootstrapTable
 * @param id  表格id
 * @param url 数据加载的url地址
 * @param obj 自定义参数
 */
function load_bootstrapTable(id,url,obj){
    var bootstrapTable_default = {
        //设置在哪里进行分页，可选值为 'client' 或者 'server',默认'client'
        sidePagination: 'client',
        //服务器数据的加载地址,默认undefined
        url: url,
        //服务器数据的请求方式 'get' or 'post',默认'get'
        method: 'post',
        //是否使用缓存,默认true,设置为 false 禁用 AJAX 数据缓存
        cache: false,
        //发送到服务器的数据编码类型,默认'application/json',post请求改为'application/x-www-form-urlencoded; charset=UTF-8'
        contentType:'application/x-www-form-urlencoded; charset=UTF-8',
        //每行的唯一标识,默认undefined
        uniqueId: "id",
        //是否显示 切换试图（table/card）按钮,默认false
        showToggle: true,
        //是否显示 刷新按钮,默认false
        showRefresh: true,
        //按钮 水平方向的位置。'left' or 'right',默认'right'
        buttonsAlign:'left',
        //是否允许排序,默认false
        sortable: true,
        //定义排序方式 'asc' 或者 'desc',默认'asc'
        sortOrder : 'asc'
    };
    //合并默认参数和自定义参数,自定义参数和默认参数存在相同值,自定义参数会覆盖默认参数
    var new_obj = $.extend({},bootstrapTable_default,obj);
    $('#'+id).bootstrapTable(new_obj);
}

/**
 * 刷新bootstrapTable
 * @param id
 * @param params
 */
function refresh_bootstrapTable(id,params){
    params = params?params:{};
    $('#'+id).bootstrapTable('refresh',{query:params});
}

/**
 * iframe弹出层
 * @param url url地址
 * @param title 窗口标题
 * @param area ['宽','高']
 * @param offset 坐标,默认auto,即垂直水平居中
 * @param callback 回调方法
 * @private
 */
function _add_movable_popup(url,title,area,offset,callback){
    if(!url){
        return;
    }
    if(!area){
        area = ['800px','500px'];
    }
    if(device.iphone()==true || device.android()==true){
        area = ['auto','400px'];
    }
    if(!offset){
        offset = 'auto';
    }
    parent.layer.open({
        type: 2,
        title: title,
        area: area,
        shade: 0,
        offset: offset,
        content: url,
        btn: ['确定'],
        yes: function(index, layero){
            //得到iframe页的窗口对象,执行iframe页的保存方法
            var iframeWin = window[layero.find('iframe')[0]['name']];
            //先默认都是调用save方法
            iframeWin.save(function(){
                //执行回调方法
                if(callback){
                    callback();
                }
            });
        }
    });
}

/**
 * 关闭弹出层,在iframe页面关闭自身
 * @private
 */
function _close_movable_popup(){
    //先得到当前iframe层的索引
    var index = parent.layer.getFrameIndex(window.name);
    //再执行关闭
    parent.layer.close(index);
}

/**
 * 阻止默认回车事件,调用自己的方法
 * @param input_id 大多为input的id
 * @param callback 要调用的方法
 * @private
 */
function _call_enter_Event(input_id,callback){
    if(input_id.substring(0,1)=='#'){
        input_id = input_id.substring(1);
    }
    $('#'+input_id).bind('keypress',function(e){
        var ev = document.all ? window.event : e;
        if(ev.keyCode==13) {
            ev.preventDefault();
            if(callback){
                callback();
            }
        }
    });
}

/**
 * 操作成功提示
 * @param tip
 * @param time
 * @param is_parent
 * @private
 */
function _tip_success(tip,time,is_parent){
    if(is_parent){
        parent.layer.msg(tip,{
            icon: 6,
            time: time?time*1000:2000
        });
    }else{
        layer.msg(tip,{
            icon: 6,
            time: time?time*1000:2000
        });
    }
}

/**
 * 操作错误提示
 * @param tip
 * @param time
 * @param is_parent
 * @private
 */
function _tip_error(tip,time,is_parent){
    if(is_parent){
        parent.layer.msg(tip,{
            icon: 5,
            time: time?time*1000:5000
        });
    }else{
        layer.msg(tip,{
            icon: 5,
            time: time?time*1000:5000
        });
    }
}

/**
 * 通用form提交
 * @param formId
 * @param callback
 * @private
 */
function _submit_form(formId,callback){
    if(!formId){
        return;
    }
    $('#'+formId).ajaxSubmit({success: function (data) {
        if(data == 'success') {
            _tip_success('操作成功!','',1);
            if(callback){
                callback();
            }
        }
        else if(data>0) {
            _tip_success('操作成功!','',1);
            if(callback){
                callback();
            }
        }
        else if(isJson(data)){
            if(data.code==1){
                _tip_success(data.msg,'',1);
                if(callback){
                    callback();
                }
            }else{
                _tip_error(data.msg,'',1);
            }
        }
        else{
            _tip_error('操作失败!','',1);
        }
    }});
}

/**
 * 通用ajax删除
 * @param url
 * @param data
 * @param callback
 * @private
 */
function _delete_item(url,data,callback){
    if(!url){
        return;
    }
    if(!data){
        data = {};
    }
    layer.confirm('确认删除吗?',function(index){
        $.ajax({
            dataType: "json",
            type: "post",
            url: url,
            data: data,
            success: function (ret){
                if(ret>0){
                    _tip_success('删除成功!');
                    if(callback){
                        callback();
                    }
                    layer.close(index);
                }
                else if(isJson(data)){
                    if(ret.code==1){
                        _tip_success(ret.msg);
                        if(callback){
                            callback();
                        }
                        layer.close(index);
                    }else{
                        _tip_error(ret.msg);
                    }
                }
                else{
                    _tip_error('删除失败!');
                }
            }
        });
    });
}

/**
 * bootstrap-editable
 * @param id 元素id
 * @param type 类型 text,textarea,select等
 * @param pk 主键id
 * @param name 更新字段名称
 * @param url 系统更新方法
 * @param validate 验证类型
 * @param title popup弹框标题
 * @private
 */
function _editable(id,type,pk,name,url,validate,title){
    var obj_id = $('#'+id);
    obj_id.editable({
        type: type,
        pk: pk,
        name:name,
        url: url,
        title:title?title:'编辑',
        validate: function(v) {
            if(validate=='must'){
                if(!$.trim(v)) return '必填!';
            }else if(validate=='number'){
                if(validator.isNumeric(v)===false) return '数字格式错误!';
            }else if(validate=='email'){
                if(validator.isEmail(v)===false) return 'email格式错误!';
            }else if(validate=='mobile'){
                if(validator.isMobilePhone(v,'zh-CN')===false) return '电话格式错误!';
            }
        },
        success: function(response, newValue) {
            if(response.code == 0) return response.msg;
        }
    });
}

/**
 * 开关按钮
 * @param obj
 * @param callback
 */
function slider_btn(obj,callback){
    if ($(obj).hasClass('on')) {
        $(obj).removeClass('on').html($(obj).data('off-text'));
    } else {
        $(obj).addClass('on').html($(obj).data('on-text'));
    }
    if(callback){
        callback();
    }
}

/**
 * 判断是否是json
 * @param obj
 * @returns {boolean}
 */
function isJson(obj){
    return typeof(obj) == "object" && Object.prototype.toString.call(obj).toLowerCase() == "[object object]" && !obj.length;
}

/**
 * 判断是否是String
 * @param obj
 * @returns {boolean}
 */
function isString(obj){
    return Object.prototype.toString.call(obj) === "[object String]"
}

/**
 * 判断是否是function
 * @param obj
 * @returns {boolean}
 */
function isFunction(obj){
    return typeof(obj)=='function' && Object.prototype.toString.call(obj)==='[object Function]'
}

/**
 * 去掉所有的html标记
 * @param str
 * @returns {string|void|XML}
 */
function delHtmlTag(str){
    str = str.replace(/<[^>]+>/g,"");
    return str.replace(/&nbsp;/ig,'');
}

//去除空白字符,并重新复制
function _trim(obj,is_assign){
    var new_obj = $('#'+obj);
    var new_content = $.trim(new_obj.val());
    if(is_assign==1){
        new_obj.val(new_content);
    }else{
        if(new_content){
            new_obj.val(new_content);
        }
    }
    return new_content;
}

$(function(){
    //步骤切换
    var _boxCon=$(".box-con");
    $(".move-login").on("click",function(){
        $(_boxCon).css({
            'marginLeft':0
        });
    });

    $(".move-signup").on("click",function(){
        $(_boxCon).css({
            'marginLeft':-320
        });
    });

    $(".move-other").on("click",function(){
        $(_boxCon).css({
            'marginLeft':-640
        });
    });

    $(".move-reset").on("click",function(){
        $(_boxCon).css({
            'marginLeft':-960
        });
    });

    $("body").on("click",".move-addinf",function(){
        $(_boxCon).css({
            'marginLeft':-1280
        });
    });
});

function _register(){
    var error_em = $('.error-notice.email');
    var error_pw = $('.error-notice.password');
    var error_nn = $('.error-notice.nickname');

    error_em.text('');
    error_pw.text('');
    error_nn.text('');

    var email = _trim('register_email');
    if(!email){
        error_em.text('邮箱不能为空!');
        return false;
    }
    if(validator.isEmail(email)===false){
        error_em.text('邮箱格式错误!');
        return false;
    }

    var nickname = _trim('nickname');
    if(!nickname){
        error_nn.text('昵称不能为空!');
        return false;
    }

    var password = _trim('password');
    if(!password){
        error_pw.text('密码不能为空!');
        return false;
    }

    if(password.length >=6 && password.length <=16 && (/^(?=.*[a-zA-Z]+)(?=.*[0-9]+)[a-zA-Z0-9]+$/.test(password))){
    }else{
        error_pw.text('密码6~16位,且为数字和字母组合!');
    }

    $.ajax({
        dataType: "json",
        type: "post",
        url: '/index/register',
        data: {
            email:email,
            nickname:nickname,
            password:password
        },
        success: function (ret){
            if(ret.status=='error'){
                switch(ret.type){
                    case 'email':
                        error_em.text(ret.text);
                        break;
                    case 'nickname':
                        error_pw.text(ret.text);
                        break;
                    case 'password':
                        error_pw.text(ret.text);
                        break;
                }
            }else{
                $('.error-notice.success').text(ret.text);
                setTimeout(function(){
                    $(".box-con").css({
                        'marginLeft':0
                    });
                },800);
            }
        }
    });
}

function _login(){
    var error_em = $('.error-notice.login_email');
    var error_pw = $('.error-notice.login_password');

    error_em.text('');
    error_pw.text('');

    var email = _trim('login_email');
    if(!email){
        error_em.text('邮箱不能为空!');
        return false;
    }
    if(validator.isEmail(email)===false){
        error_em.text('邮箱格式错误!');
        return false;
    }

    var password = _trim('login_password');
    if(!password){
        error_pw.text('密码不能为空!');
        return false;
    }

    $.ajax({
        dataType: "json",
        type: "post",
        url: '/index/login',
        data: {
            email:email,
            password:password
        },
        success: function (ret){
            if(ret.status=='error'){
                switch(ret.type){
                    case 'email':
                        error_em.text(ret.text);
                        break;
                    case 'password':
                        error_pw.text(ret.text);
                        break;
                }
            }else{
                $('.error-notice.login_success').text(ret.text);
                location.href=location.href;
            }
        }
    });
}

function _sign_out(uid){
    if(confirm('确认退出登录吗?')){
        $.ajax({
            dataType: "text",
            type: "post",
            url: '/index/sign_out',
            data: {
                uid:uid
            },
            success: function (ret) {
                if (ret== 'success') {
                    location.href = location.href;
                }
            }
        });
    }
}

function tip_success(msg_or_json, time, closeFunc){
    if(!msg_or_json){
        msg_or_json = '操作成功';
    }

    if(!time || time<=0){
        time = 4;
    }

    if(!isFunction(closeFunc)){
        closeFunc = null;
    }

    tip(msg_or_json,time,null,closeFunc,'success');
}

function tip_error(msg_or_json, time, closeFunc){
    if(!msg_or_json){
        msg_or_json = '操作失败';
    }

    if(!time || time<=0){
        time = 10;
    }

    if(!isFunction(closeFunc)){
        closeFunc = null;
    }

    tip(msg_or_json,time,null,closeFunc,'error');
}

function tip(msg_or_json, time, url, closeFunc, tip_type){
    time = time?time*1000:2000;
    var str = '';
    if(isString(msg_or_json)){
        str = msg_or_json;
    }else if(isJson(msg_or_json)){
        if(msg_or_json.err_text){
            str = msg_or_json.err_text;
        }
    }

    var class_name = 'info';
    if(tip_type=='success'){
        class_name = 'success';
    }else if(tip_type=='error'){
        class_name = 'error';
    }

    var tip_title = '操作提示';
    if(tip_type=='success'){
        tip_title = '操作成功';
    }else if(tip_type=='error'){
        tip_title = '操作异常';
    }

    var _unique_id = $.gritter.add({
        title:	tip_title,
        text:	str ,
        time: time,
        class_name: class_name,
        after_open: function(e){},
        before_close:function(e){
            if(closeFunc){
                closeFunc();
            }
            if(url){
                setTimeout(function(){
                    window.location.href = url;
                },800);
            }
        },
        after_close:function(e){}
    });

    return _unique_id;
}

function tip_end(){
    $.gritter.removeAll();
}