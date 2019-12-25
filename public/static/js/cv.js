/**
 * Created by kangly on 2018/1/26.
 */
var xinm = new Array();
xinm[0] = '1';
xinm[1] = '2';
xinm[2] = '3';
xinm[3] = '4';
xinm[4] = '5';
xinm[5] = '6';
xinm[6] = '7';
xinm[7] = '8';
xinm[8] = '9';
xinm[9] = '10';
xinm[10] = '11';
xinm[11] = '12';
xinm[12] = '13';
xinm[13] = '14';
xinm[14] = '15';
xinm[15] = '16';
xinm[16] = '17';
xinm[17] = '18';
xinm[18] = '19';
xinm[19] = '20';
xinm[20] = '21';
xinm[21] = '22';
xinm[22] = '23';
xinm[23] = '24';
xinm[24] = '25';
xinm[25] = '26';
xinm[26] = '27';
xinm[27] = '28';
xinm[28] = '29';
xinm[29] = '30';
xinm[30] = '31';
xinm[31] = '32';
xinm[32] = '33';
xinm[33] = '34';
xinm[34] = '35';
xinm[35] = '36';
xinm[36] = '37';
xinm[37] = '38';
xinm[38] = '39';
xinm[39] = '40';
xinm[40] = '41';
xinm[41] = '42';
xinm[42] = '43';
xinm[43] = '44';
xinm[44] = '45';
xinm[45] = '46';
xinm[46] = '47';
xinm[47] = '48';
xinm[48] = '49';
xinm[49] = '50';
xinm[50] = '51';
xinm[51] = '52';
xinm[52] = '53';
xinm[53] = '54';
xinm[54] = '55';
xinm[55] = '56';
xinm[56] = '57';
xinm[57] = '58';
xinm[58] = '59';
xinm[59] = '60';
xinm[60] = '61';
xinm[61] = '62';
xinm[62] = '63';
xinm[63] = '64';
xinm[64] = '65';
xinm[65] = '66';
xinm[66] = '67';
xinm[67] = '68';
xinm[68] = '69';
xinm[69] = '70';

var nametxt = $('.name');
var pcount = xinm.length;//参加人数
var runing = true;
var num = 0;
var t = 0;

//开始停止
function start() {
    if (runing) {
        runing = false;
        $('#btntxt').removeClass('start').addClass('stop');
        $('#btntxt').html('停止');
        startNum()
    } else {
        runing = true;
        $('#btntxt').removeClass('stop').addClass('start');
        $('#btntxt').html('开始');
        stop();
        zd();
    }
}

//循环参加名单
function startNum() {
    num = Math.floor(Math.random() * pcount);
    t = setTimeout(startNum, 0);
    nametxt.html(xinm[num]);
}

//停止跳动
function stop() {
    pcount = xinm.length;
    clearInterval(t);
    t = 0;
}

function zd() {
    if(pcount <= 0){
        alert("抽奖已经结束!");
        return false;
    }
    /*var this_no = xinm[num];
     if(typeof this_no == 'undefined'){
     this_no = $('.name').text();
     }*/
    var this_no = $('.name').text();
    $('.list').prepend("<span>"+this_no+"</span>,");
    //将已中奖者从数组中"删除",防止二次中奖
    xinm.splice($.inArray(this_no, xinm), 1);
}