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

$(function() {
    "use strict";
    // Theme options
    // sidebar-hover
    $(".left-sidebar").hover(
        function() {
            $(".navbar-header").addClass("expand-logo");
        },
        function() {
            $(".navbar-header").removeClass("expand-logo");
        }
    );
    // this is for close icon when navigation open in mobile view
    $(".nav-toggler").on('click', function() {
        $("#main-wrapper").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
    });
    $(".nav-lock").on('click', function() {
        $("body").toggleClass("lock-nav");
        $(".nav-lock i").toggleClass("mdi-toggle-switch-off");
        $("body, .page-wrapper").trigger("resize");
    });
    $(".search-box a, .search-box .app-search .srh-btn").on('click', function() {
        $(".app-search").toggle(200);
        $(".app-search input").focus();
    });
    // Right sidebar options
    $(function() {
        $(".service-panel-toggle").on('click', function() {
            $(".customizer").toggleClass('show-service-panel');
        });
        $('.page-wrapper').on('click', function() {
            $(".customizer").removeClass('show-service-panel');
        });
    });
    // This is for the floating labels
    $('.floating-labels .form-control').on('focus blur', function(e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');
    //tooltip
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });
    // Resize all elements
    $("body, .page-wrapper").trigger("resize");
    $(".page-wrapper").show();
    // To do list
    $(".list-task li label").click(function() {
        $(this).toggleClass("task-done");
    });
    /* This is for the mini-sidebar if width is less then 1170*/
    var setsidebartype = function() {
        var width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width;
        if (width < 1170) {
            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
        } else {
            $("#main-wrapper").attr("data-sidebartype", "full");
        }
    };
    $(window).ready(setsidebartype);
    $(window).on("resize", setsidebartype);
    /* This is for sidebartoggler*/
    $('.sidebartoggler').on("click", function() {
        $("#main-wrapper").toggleClass("mini-sidebar");
        if ($("#main-wrapper").hasClass("mini-sidebar")) {
            $(".sidebartoggler").prop("checked", !0);
            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
        } else {
            $(".sidebartoggler").prop("checked", !1);
            $("#main-wrapper").attr("data-sidebartype", "full");
        }
    });
});

// Auto select left navbar
$(function() {
    "use strict";
    var url = window.location + "";
    var path = url.replace(window.location.protocol + "//" + window.location.host + "/", "");
    var element = $('ul#sidebarnav a').filter(function() {
        return this.href === url || this.href === path;// || url.href.indexOf(this.href) === 0;
    });
    element.parentsUntil(".sidebar-nav").each(function (index)
    {
        if($(this).is("li") && $(this).children("a").length !== 0)
        {
            $(this).children("a").addClass("active");
            $(this).parent("ul#sidebarnav").length === 0
                ? $(this).addClass("active")
                : $(this).addClass("selected");
        }
        else if(!$(this).is("ul") && $(this).children("a").length === 0)
        {
            $(this).addClass("selected");

        }
        else if($(this).is("ul")){
            $(this).addClass('in');
        }

    });

    element.addClass("active");

    $('#sidebarnav a').on('click', function (e) {
        if (!$(this).hasClass("active")) {
            // hide any open menus and remove all other classes
            $("ul", $(this).parents("ul:first")).removeClass("in");
            $("a", $(this).parents("ul:first")).removeClass("active");

            // open our new menu and add the open class
            $(this).next("ul").addClass("in");
            $(this).addClass("active");
        }
        else if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $(this).parents("ul:first").removeClass("active");
            $(this).next("ul").removeClass("in");
        }
    });

    $('#sidebarnav >li >a.has-arrow').on('click', function (e) {
        e.preventDefault();
    });
});

var itn_tab_Page_Idx = 1;
var itn_tabContainerId_default = "tab_content";
var itn_tabPageContainerId_default = "tab_content_page";
var _tab_default_top_scroll_val = -1;
var _tab_link_page_id = "href";
var _tab_link_page_url = "page";
var _tab_delay_reload = false;

//当前显示标签页id,默认#tab_home
var _default_tab_page_id = '#tab1 ';
var _current_tab_page_id = _default_tab_page_id;

//上一个显示的标签，默认与_current_tab_page_id相同
var _last_show_tab_page_id;
var _sub_tab_opener_Page_id;

//正在关闭(与_current相同)，或已经关闭（上一个）tabId
var _closing_tab_page_id;

//马上显示（一定会显示），或已经显示（显示后与_current相同）tabId
//暂时未处理新建tab时，对_activating_tab_page_id的赋值
var _activating_tab_page_id;

function _tab_reload_current(){
    _tab_reload_page(_current_tab_page_id);
}

function _tab_reload_last(){
    _tab_reload_page(_last_show_tab_page_id);
}

function _tab_reload_opener(){
    if(_sub_tab_opener_Page_id && _sub_tab_opener_Page_id.length>0)
        _tab_reload_page(_sub_tab_opener_Page_id);
    else
        _tab_reload_page(_last_show_tab_page_id);
}

function _tab_reload_page(_page_id){
    var tempHref = _page_id;
    if(tempHref && tempHref.length>0)
        tempHref = tempHref.substring(0,tempHref.length-1);
    var tabLinkA = $('#'+itn_tabContainerId_default).find('li a['+_tab_link_page_id+'="'+tempHref+'"]');
    _tab_load_page_content(tabLinkA);
}

function _tab_close_current_and_reload_opener(){
    _tabCloseByPageId(_current_tab_page_id);
    _tab_reload_opener();
}

//关闭当前，尝试只刷新opener的tab中的grid,包含pageNo
function _tab_close_current_and_reload_opener_grid(){
    _tabCloseByPageId(_current_tab_page_id);
    if(_tab_delay_reload)
        setTimeout(_tab_reload_opener_grid, 700);
    else
        _tab_reload_opener_grid();
}

function _tab_reload_opener_grid(){
    _tab_reload_tab_grid(_sub_tab_opener_Page_id);
}

function _tab_reload_tab_grid(tabPageId){
    var reloadGridSuccess = false;
    var openerContainer = $(tabPageId);
    if(openerContainer && openerContainer.length>0){
        var anyGrid = _pad_findGridByContainerId(openerContainer.attr('id'));
        if(anyGrid && anyGrid.length>0){
            //为加载的grid table添加containerId属性
            var gridContainerId = anyGrid.attr('containerId');
            if(gridContainerId && gridContainerId.length>0){
                reloadGridSuccess = true;
                _pad_grid_loadPage(gridContainerId);
            }
        }
    }
    return reloadGridSuccess;
}

//刷新当前页面中的第一个grid
function _tab_reload_current_tab_grid(){
    _tab_reload_tab_grid(_current_tab_page_id);
}

//重新设置tab的链接
function _tabLocateUrl(url, containerId){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;
    var tabLink = $('#'+itn_tabContainerId_default+'>ul.nav-tabs>li>a['+_tab_link_page_id+'="'+containerId+'"]');
    if(tabLink.length>0){
        tabLink.attr(_tab_link_page_url,url);
        _tab_load_page_content(tabLink,null,null,true);
    }
}

//显示新tab
function _tabShow(title,contentUrl,pageId,tabContainerId,tabPageContainerId,params,callBack){
    if(!tabContainerId)tabContainerId = itn_tabContainerId_default;
    if(!tabPageContainerId)tabPageContainerId = itn_tabPageContainerId_default;

    _sub_tab_opener_Page_id = _current_tab_page_id;

    var tabContainer = $('#'+tabContainerId);
    var tabList = tabContainer.find('ul:first');
    var tabPageContainer = $('#'+tabPageContainerId);

    if(!pageId){
        pageId = 'subPage_'+itn_tab_Page_Idx;
        itn_tab_Page_Idx++;
    }
    if(!title)title=pageId;
    var newTab;
    var newTabContent;
    var tabSwitcher;

    if($('#'+pageId).length>0){
        newTab = tabList.find("li[pageId='"+pageId+"']");
        tabSwitcher = newTab.find('a:first');
        //注释掉下面3行，并添加trigger和return，可以防止重新加载已有页面
        newTabContent = $('#'+pageId);
        tabSwitcher.text(title);
        tabSwitcher.attr(_tab_link_page_url,contentUrl);
        //tabSwitcher.trigger('click');
        //return;
    }else{
        newTab = $('<li pageId="'+pageId+'" class="sub"></li>');
        tabSwitcher = $('<a data-toggle="tab" '+_tab_link_page_id+'="#'+pageId+'" '+_tab_link_page_url+'="'+contentUrl+'">'+title+' </a>');
        newTab.append(tabSwitcher);
        tabList.append(newTab);

        var tabClose = $('<span id="tab_close_'+ pageId +'" class="sub_close"></span>');
        newTab.append(tabClose);
        tabClose.bind('click',function(){
            var tabObj = $(this).parent();
            var tabListObj = tabObj.parent();
            var pageId = tabObj.attr('pageId');

            if(tabObj.hasClass('active') || tabObj.hasClass('current')){
                var tempHref = _last_show_tab_page_id;
                if(tempHref && tempHref.length>0)
                    tempHref = tempHref.substring(0,tempHref.length-1);
                var lastTabLink = tabListObj.find('li a['+_tab_link_page_id+'="'+tempHref+'"]');

                var prevTabLink;
                if(lastTabLink.length>0){
                    prevTabLink = lastTabLink;
                }else{
                    var prevTab = tabObj.prev();
                    prevTabLink = prevTab.find('a:first');
                }

                _closing_tab_page_id = _current_tab_page_id;
                if(prevTabLink && prevTabLink.length>0){
                    prevTabLink.tab('show');
                    //prevTabLink.trigger('click');
                    _activating_tab_page_id = prevTabLink.attr(_tab_link_page_id);
                }
            }
            tabObj.remove();
            $('#'+pageId).remove();

            if(tabListObj.children('li').length==1){
                //暂时在关闭时不隐藏
                //tabList.slideUp('fast');
                //tabList.addClass('off_show');
                _tab_default_top_scroll_val = -1;
            }
        });

        newTabContent = $('<div id="'+pageId+'" class="tab-pane new_tab" ></div>');
        tabPageContainer.append(newTabContent);
    }

    //load content for new tab
    _tab_load_page_content(tabSwitcher,params,callBack);

    if(tabList.hasClass('off_show')){
        tabList.slideDown('fast');
        tabList.removeClass('off_show');
        _tab_default_top_scroll_val = tabList.offset().top;
    }

    return pageId;
}

function _tab_page_init(tabLinkA, tabPageContainerId){
    var jLinkA = $(tabLinkA);
    var pageId = jLinkA.attr(_tab_link_page_id);
    if(pageId!=''){
        return;
    }else{
        pageId = 'subPage_'+itn_tab_Page_Idx;
        itn_tab_Page_Idx++;
        jLinkA.attr(_tab_link_page_id,'#'+pageId);
    }

    if(!tabPageContainerId)tabPageContainerId = itn_tabPageContainerId_default;

    var tabPageContainer = $('#'+tabPageContainerId);

    var newTabContent = $('<div id="'+pageId+'" class="tab-pane new_tab" ></div>');
    tabPageContainer.append(newTabContent);

    _tab_load_page_content(jLinkA);
}

function _tab_load(tabLinkA, params, callback, tabPageContainerId){
    var jLinkA = $(tabLinkA);
    var pageId = jLinkA.attr(_tab_link_page_id);
    if(pageId==''){
        return;
    }

    _tab_load_page_content(jLinkA,params,callback);
}

function _tab_load_once(tabLinkA, tabPageContainerId){
    var jLinkA = $(tabLinkA);
    var pageId = jLinkA.attr(_tab_link_page_id);
    if(pageId==''){
        return;
    }
    var loadCount = jLinkA.attr('load_count');
    if(loadCount && loadCount>0){
        jLinkA.tab('show');
    }else{
        jLinkA.attr('load_count',1);
        _tab_load_page_content(jLinkA);
    }
}

//页面内的tab元素加载
function _tab_load_page_content(_tab_page_tabA,params,callBack,isLoadNewTab){
    var _tab_page_tabUl;
    var _tab_page_tabLi;
    if(_tab_page_tabA && _tab_page_tabA.length>0){
        _tab_page_tabLi = _tab_page_tabA.parent();
        _tab_page_tabUl = _tab_page_tabLi.parent();
    }else{
        _tab_page_tabUl = $('#'+itn_tabContainerId_default+'>ul.nav-tabs');
        _tab_page_tabLi	= _tab_page_tabUl.children('li:first');
        _tab_page_tabA = _tab_page_tabLi.find('a:first');

        //这种情况应该是发生在页面初次加载默认tab时，给_current_tab_page_id赋值
        _current_tab_page_id = _tab_page_tabA.attr(_tab_link_page_id) + ' ';
    }

    _tab_page_tabA.attr('load_count',1);

    var _tab_page_load_url = _tab_page_tabA.attr(_tab_link_page_url);
    var _tab_page_load_container = _tab_page_tabA.attr(_tab_link_page_id);

    var thisTabCallBack = new _tab_after_load_event(callBack);

    var keepParams = true;
    if(isLoadNewTab)
        keepParams = false;

    _pad_all_loadPage(_tab_page_load_url,_tab_page_load_container,keepParams,params,function(pageContainerId){
        if(thisTabCallBack.userCallBackFunc)
            thisTabCallBack.userCallBackFunc(pageContainerId);
        //switch to new tab，放在这里可以避免每次没加载完html内容就显示时页面会跳到最上面
        _tab_page_tabA.tab('show');
        //thisTabCallBack.scrollContent(pageContainerId);
    });
}

function _tab_after_load_event(userCallBack){
    this.userCallBackFunc = userCallBack;
    this.scrollContent=_tab_scrollTabContent;
}

//滚动到tab内容顶部
function _tab_scrollTabContent(pageContainerId){
    if(_tab_default_top_scroll_val!=-1)
        $(window).scrollTop(_tab_default_top_scroll_val);
}

function _load_tab_page_content(_tab_page_tabA,params,callback){
    if(!_tab_page_tabA){
        //默认的元素标签
        _tab_page_tabA = $('#myTab a[page]:first');
    }
    _tab_load_page_content(_tab_page_tabA,params,callback);
}

function _tabCloseByPageId(pageId){
    if(!pageId)pageId=_current_tab_page_id;
    if(pageId.substring(0,1)=='#')pageId = pageId.substring(1);

    $('#tab_close_'+pageId).trigger('click');
}

//数据列表检索排序字符串保存的键值(值以$.data的方式保存在_current_tab_page_id对应的对象上)
var _pad_adv_filter_id = "_pad_adv_filter_id";
//数据列表检索条件数据
var _pad_search_params_id = "_pad_search_params_id";
//页面初次加载时保存参数
var _pad_page_base_params_id = "_pad_page_base_params_id";
var _pad_grid_page_size = "gridPageSize";

//给指定元素加载内容
function _p_a_load(url,pageContainerId,keepParams,data,callBack, isGridInit){
    _pad_all_loadPage(url,pageContainerId,keepParams,data,callBack, isGridInit);
}

//keepParams 是否保留container上缓存的参数，首次加载时一般为false
function _pad_all_loadPage(url,pageContainerId,keepParams,data,callBack, isGridInit){
    if(typeof(pageContainerId)=='string'){
        if(pageContainerId.substring(0,1)=='#')
            pageContainerId = pageContainerId.substring(1);
    }

    var containerObj = find_jquery_object(pageContainerId);

    if(!data){
        data = {};
    }

    var initPageSize = containerObj.attr(_pad_grid_page_size);
    if(initPageSize && initPageSize>0){
        //有初始化containerObj 中grid的pageSize
        data = _pad_add_param_to_post_data(data,'gridPageSize',initPageSize);
    }

    //将容器id传入到action的loadPageId，在页面初始化脚本中可以使用
    if(url.indexOf('loadPageId')==-1 && (!data || !data.loadPageId) && (typeof(data)!='string'||data.indexOf('loadPageId')==-1)){
        data = _pad_add_param_to_post_data(data,'loadPageId',pageContainerId);
    }

    if(isGridInit && data){
        containerObj.data(_pad_search_params_id, data);
    }

    if(!keepParams)
        _pad_clear_container_old_data(pageContainerId);

    var pageBaseParams = containerObj.data(_pad_page_base_params_id);

    var param_idx = url.indexOf('?');
    if(param_idx!=-1)
    {
        var param_idx_2 = url.indexOf('#');

        var params_str = '';
        if(param_idx_2!=-1){
            params_str = url.substring(param_idx+1,param_idx_2);
        }else{
            params_str = url.substring(param_idx+1);
        }
        url = url.substring(0,param_idx);

        if(params_str.length>0){
            if(!data){
                data = {};
            }else if(Object.prototype.toString.call(data) === "[object String]"){
                params_str = params_str + '&' + data;
                data = {};
            }

            var param_arr = params_str.split('&');
            for(pi=0;pi<param_arr.length;pi++){
                var p_key_val = param_arr[pi].split('=');
                if(p_key_val.length>1){
                    data[p_key_val[0]] = p_key_val[1];
                }
            }
        }
    }

    if(!pageBaseParams && data){
        pageBaseParams = data;
    }else{
        pageBaseParams = _pad_mergeJsonObject(pageBaseParams, data);
    }
    containerObj.data(_pad_page_base_params_id, pageBaseParams);

    $.ajax({
        url:url ,
        type: "post",
        data: pageBaseParams,
        cache:false,
        success:function(html){
            if(html && isJson(html)){
                //返回错误异常
                if(html.err_text){
                    _tip_error(html.err_text);
                    _hide_top_loading();
                }
                return;
            }

            _pad_add_pageInfo_to_loadPageHtml(html, pageContainerId, url);

            //处理如果html中有grid，为grid加上containerId
            var tempIdx1 = html.indexOf('<table');
            if(tempIdx1!=-1){
                tempIdx1 = tempIdx1 + 6;
                var tempIdx2 = html.indexOf('>',tempIdx1);
                var tempIdx3 = html.indexOf('table',tempIdx1);
                if(tempIdx3!=-1 && tempIdx3< tempIdx2){
                    //尝试准确的定位"table.table:first"的table
                    html = html.substring(0,tempIdx1) + ' containerId="#'+pageContainerId+'"' + html.substring(tempIdx1);
                }
            }

            containerObj.html(html);
            containerObj.trigger('new_content_load');

            //ajax刷新分页
            _update_pager_click_event(containerObj);

            var anyGrid = _pad_findGridByContainerId(pageContainerId);
            if(anyGrid.length>0){
                add_event_for_jm_table(anyGrid);
            }

            //添加clearable输入框清除按键
            _pad_add_clearable_input_btn(pageContainerId);

            if(callBack){
                callBack(pageContainerId);
            }
        }
    }).always(function(){});
}

function _pad_all_reload_my_container(obj,more_parems,callback){
    var jobj = find_jquery_object(obj);
    var parent_container = jobj.parents('.tab-pane.active');
    _pad_all_reloadPage(parent_container.attr('id'), more_parems, callback);
}

function _pad_all_reloadPage(pageContainerId,more_parems,callback){
    var container = find_jquery_object(pageContainerId);
    if(container){
        var url = container.attr('content_url');
        _pad_all_loadPage(url, pageContainerId, true, more_parems, callback);
    }
}

function _pad_all_set_active_tab_title(title, pageContainerId){
    var more = '';
    if(pageContainerId && pageContainerId!=''){
        more = '[href="#'+pageContainerId+'"]';
    }

    var tab = $('.nav-tabs > li.active > a'+more);
    if(tab.length>0){
        tab.text(title);
    }
}

function _pad_add_clearable_input_btn(containerId){
    var container = find_jquery_object(containerId);
    container.find('input.clearable').each(function(){
        $(this).wrap('<div class="clearable_container"></div>');
        var clear_btn = $('<i class="icon-remove clear_btn"></i>');
        clear_btn.click(function(){
            $(this).prev('input').val('');
        });
        $(this).after(clear_btn);
    });
}

function add_event_for_jm_table(gridTable){
    var gridContainerId = gridTable.attr('containerId');
    add_event_for_jm_table_sort(gridContainerId);
}

function _pad_findGridByContainerId(containerId){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;
    var containerObj = $(containerId);
    var anyGrid = containerObj.find('table.table:first');

    if(anyGrid.length>0){
        var ori_containerId = anyGrid.attr('containerId');
        if(!ori_containerId || ori_containerId==''){
            anyGrid.attr('containerId',containerId);
        }
    }

    return anyGrid;
}

function add_event_for_jm_table_sort(containerId){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;

    var container = $(containerId);
    var page_params = container.data(_pad_page_base_params_id);
    if(page_params && page_params.sort_column){
        var on_sorting = container.find('[sort_column="'+page_params.sort_column+'"]');
        if(on_sorting.length==1){
            on_sorting.attr('sort_type', page_params.sort_type);
        }
    }

    container.find('.tab_sorter').each(function(){
        var column = $(this);
        var sort_column = column.attr('sort_column');
        if(sort_column!=''){
            var column_table = column.parents('[content_url]:first');
            var sort_type = column.attr('sort_type');
            column_table.removeClass('icon-sort-up icon-sort-down');
            if(sort_type=='asc'){
                column.addClass('icon-sort-up');
            }else if(sort_type=='desc'){
                column.addClass('icon-sort-down');
            }else{
                column.addClass('icon-sort');
            }

            column.unbind('click').bind('click',function(){
                _show_top_loading();
                var thiscolumn = $(this);
                var thissort = thiscolumn.attr('sort_column');
                var thistype = thiscolumn.attr('sort_type');
                if(thiscolumn.is('.icon-sort-down')){
                    thistype = 'asc';
                }else if(thiscolumn.is('.icon-sort-up')){
                    thistype = '';
                }else{
                    thistype = 'desc';
                }
                thiscolumn.attr('sort_type',thistype);

                var table_id = column_table.attr('id');
                var table_url = column_table.attr('content_url');
                var params = column_table.data(_pad_page_base_params_id);
                if(!params){
                    params = {};
                }

                params = _pad_add_param_to_post_data(params,'sort_column',thissort);
                params = _pad_add_param_to_post_data(params,'sort_type',thistype);

                _p_a_load(table_url, table_id, null, params, function(){
                    _hide_top_loading();
                });
            });
        }
    });
}

/**
 * ajax刷新分页
 * @param container
 * @private
 */
function _update_pager_click_event(container){
    var pagerObjs = container.find('.pagination.in_tab');
    pagerObjs.each(function(){
        $(this).find('a').each(function() {
            $(this).click(function (event) {
                var url = $(this).attr('href');
                _pad_all_loadPage(url,container.attr('id'),true);
                return false;//阻止链接跳转
            });
        });
    });
}

function _pad_clear_container_old_data(containerId){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;

    var container = $(containerId);

    container.attr('content_url','');
    //不去掉就没法设置pageSize
    //container.removeAttr(_pad_grid_page_size);
    container.removeAttr('pageNo');

    container.removeData(_pad_adv_filter_id);
    container.removeData(_pad_search_params_id);
    container.removeData(_pad_page_base_params_id);

    try{
        //container.removeData(_grid_row_selected_row_ids);
    }catch(e){
        alert(e);
    }
}

function _pad_add_pageInfo_to_loadPageHtml(jqHtml, pageContainerId, url){
    var container = find_jquery_object(pageContainerId);
    container.attr('content_url',url);
}

function _pad_grid_loadPage(containerId,params){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;

    //params 是列表上方检索条件的值
    //如果params有效，则判断为点击搜做按键，pageNo条件清空,从新记录检索条件
    if(!params){
        var savedParam = $(containerId).data(_pad_search_params_id);
        if(!savedParam)
            params = {};
        else
            params = savedParam;
    }else{
        //保存检索条件
        $(containerId).data(_pad_search_params_id, params);
        //kill pageNo attribute
        $(containerId).attr('pageNo','1');
    }

    //处理高级搜索、排序（导出）处产生的设定
    var gridAdvFilterStr = $(containerId).data(_pad_adv_filter_id);
    if(gridAdvFilterStr && gridAdvFilterStr.length>0)
    {
        //将params清空，即如果有高级搜索条件，忽略普通搜索
        //params = {};
        params = combineParams(params,gridAdvFilterStr);

        params['advFilterStr'] = gridAdvFilterStr;
    }

    //处理页面加载原始参数，其实可以与search_params合并处理
    var page_base_params = $(containerId).data(_pad_page_base_params_id);
    if(page_base_params){
        params = _pad_mergeJsonObject(page_base_params, params);
    }

    //处理当前pageNo
    var reloadUrl = $(containerId).attr('content_url');
    var pageNo = $(containerId).attr('pageNo');
    if(!pageNo)
        pageNo = 1;

    //set pageNo to post attribute 'page'
    params.page=pageNo;

    _pad_all_loadPage(reloadUrl,containerId,true,params);
}

function _pad_mergeJsonObject(baseData, newData){
    if(!baseData)
        return newData;
    if(!newData)
        return baseData;

    var resultJsonObject={};
    for(var attr in baseData){
        resultJsonObject[attr]=baseData[attr];
    }
    for(var attr in newData){
        resultJsonObject[attr]=newData[attr];
    }

    return resultJsonObject;
}

function combineParams(params, advFilterStr){
    if(!advFilterStr || advFilterStr=='')
        return params;

    var newParams = {};
    if(params){
        for(var paramKey in params){
            var keyMark = paramKey + '_';
            if(advFilterStr.length<=keyMark.length || advFilterStr.substring(0,keyMark.length)!=keyMark){
                newParams[paramKey] = params[paramKey];
            }
        }
    }

    newParams['advFilterStr'] = advFilterStr;

    return newParams;
}

var _ready_to_paste = 0;
//添加input事件，只允许输入整数和小数
function _pad_addInputCheckNumEvent(obj,onlyInteger){
    var jObj = find_jquery_object(obj);

    if(jObj==null || jObj.length==0){
        return;
    }

    if(onlyInteger){
        jObj.keyup(function(event){
            $(this).val(fix_only_num($(this).val()));
        }).blur(function(event){
            $(this).val(fix_only_num($(this).val()));
        }).focus(function() {
            this.style.imeMode = 'disabled';
        });

        return;
    }

    jObj.keydown(function(event) {
        var keyCode = event.which;
        if(keyCode==17 || keyCode==224){
            _ready_to_paste = 1;
            return true;
        }else if(keyCode==86 && _ready_to_paste==1){
            _ready_to_paste = 0;
            return true;
        }
        var oldVal = $(this).val();
        if(keyCode==9){
            return true;
        }
        if(onlyInteger && keyCode == 190)
            return false;
        if(oldVal.indexOf('.')>0 && keyCode == 190)
            return false;
        if(oldVal=='' && keyCode == 190)
            return false;
        if (keyCode == 46 || keyCode == 8 || keyCode == 190 || (keyCode >= 48 && keyCode <= 57) || (keyCode >= 96 && keyCode <= 105) || keyCode == 110) {
            return true;
        }else {
            return false;
        }
    }).focus(function() {
        this.style.imeMode = 'disabled';
    }).keyup(function(event){
        if(isNaN($(this).val())){
            $(this).val('');
        }
    });
}

function fix_only_num(value){
    return value.replace(/[^\d]/g,'');
}

function find_jquery_object(obj){
    var jObj = null;
    //check if obj is just id
    if(obj instanceof jQuery){
        jObj = obj;
    }else{
        if(typeof(obj)=='string'){
            if(obj.substring(0,1)!='#')
                obj = '#' + obj;
            jObj = $(obj);
        }else{
            jObj = $(obj);
        }
    }

    return jObj;
}

function _pad_mergeJson(jBase, jAdd){
    if(!jBase)
        return jAdd;
    if(!jAdd)
        return jBase;

    var resultJsonObject={};
    for(var attr in jBase){
        resultJsonObject[attr]=jBase[attr];
    }

    for(var attr in jAdd){
        resultJsonObject[attr]=jAdd[attr];
    }

    return resultJsonObject;
}

function _pad_add_param_to_post_data(data, paramName, paramValue){
    if(!data || data.length==0){
        data = paramName+'='+paramValue;
    }else{
        if(typeof(data)=='string'){
            if(data!=''){
                if(data.substring(0,1)=='&'){
                    data = data.substring(1);
                }
                if(data.substring(data.length-1)=='&'){
                    data = data.substring(0,data.length-1);
                }

                if(data!=''){
                    var param_arr = data.split('&');
                    data = {};
                    for(pi=0;pi<param_arr.length;pi++){
                        var p_key_val = param_arr[pi].split('=');
                        if(p_key_val.length>1){
                            data[p_key_val[0]] = p_key_val[1];
                        }
                    }
                }
            }
        }

        data[paramName] = paramValue;
    }
    return data;
}

function _pad_put_pageUuid(containerId, pageUuid){
    if(!pageUuid || pageUuid.length==0)
        return;

    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;

    var container = $(containerId);

    container.data('pageUuid',pageUuid);
}

function _pad_get_pageUuid(containerId){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;

    var container = $(containerId);

    var pageUuid = container.data('pageUuid');

    if(!pageUuid && isNaN(pageUuid))
        pageUuid = '';

    return pageUuid;
}

function _tab_close_if_one_tab(){
    $('.widget-title').each(function(){
        var tab_li = $(this).find('ul.nav > li');
        if(tab_li.length==1){
            $(this).addClass('hide');
        }
    });
}

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
        area = ['900px','600px'];
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
        maxmin: true,
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
function _call_enter_event(input_id,callback){
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

function _show_top_loading(){
    $('#top_loading_mark').fadeIn('fast');
}

function _hide_top_loading(){
    $('#top_loading_mark').fadeOut('fast');
}