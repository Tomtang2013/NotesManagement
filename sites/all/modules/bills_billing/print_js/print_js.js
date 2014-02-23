var LODOP;

function myInvoicePreview(jsonobj) {
    InvoiceCreatePrintPage(jsonobj);
    LODOP.PREVIEW();
};
function myInvoiceDesign(jsonobj) {
    InvoiceCreatePrintPage(jsonobj);
    LODOP.PRINT_DESIGN();
};

function InvoiceCreatePrintPage(jsonobj) {
    LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    LODOP.PRINT_INITA("1mm","1mm","201.6mm","127mm","浙江省地方税务局通用机打发票");
    LODOP.SET_PRINT_STYLE("FontColor","#0000FF");
    LODOP.ADD_PRINT_SHAPE(2,"30mm","20mm","150mm","82mm",0,1,"#800000");
    LODOP.ADD_PRINT_SHAPE(3,"6.9mm","79mm","30mm","20mm",0,1,"#FF0000");

    LODOP.ADD_PRINT_TEXT("8.5mm","40mm","108mm","7.9mm","浙江省地方税务局通用机打发票");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",15);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
    LODOP.ADD_PRINT_TEXT("18.3mm","81mm","26.5mm","6.6mm","发 票 联");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
    LODOP.ADD_PRINT_TEXT("17.2mm","123.6mm","18.8mm","5.3mm","发票代码");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
    LODOP.ADD_PRINT_TEXT("24.2mm","123.6mm","18.8mm","5.3mm","发票号码");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
    LODOP.ADD_PRINT_TEXT("22.8mm","21.7mm","19.3mm","5.3mm","开票日期：");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
    LODOP.ADD_PRINT_TEXT("46.6mm","170.9mm","7.1mm","32mm","第一联发票联");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
    LODOP.ADD_PRINT_TEXT("31mm","14.6mm","4.5mm","80.4mm","浙地税印130855×13.08×500000份×3联");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"Alignment",2);


    LODOP.ADD_PRINT_TEXT("17.2mm","147.1mm","29.6mm","5.3mm","233001321133");
    LODOP.SET_PRINT_STYLEA(0,"FontName","System");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#FF0000");

    LODOP.ADD_PRINT_TEXT("24.2mm","147.1mm","29.6mm","5.3mm","04367551");
    LODOP.SET_PRINT_STYLEA(0,"FontName","System");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#FF0000");



    LODOP.ADD_PRINT_TEXT("22.8mm","40.5mm","39.7mm","5.3mm",jsonobj.today);
    LODOP.ADD_PRINT_TEXT("37.6mm","24.9mm","26.5mm","5.3mm",jsonobj.name);
    for(var i=0;i<jsonobj.item.length;i++){

        LODOP.ADD_PRINT_TEXT((8.2*i+45.5).toString()+"mm","24.9mm","26.5mm","5.3mm",jsonobj.item[i].item);
        LODOP.ADD_PRINT_TEXT((8.2*i+45.5).toString()+"mm","66.7mm","70mm","5.3mm",jsonobj.item[i].amount);
    }


};
function myInvoiceAddHtml() {
    LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    LODOP.PRINT_INIT("tobyqiu的打印");
    LODOP.ADD_PRINT_HTM(10,55,"100%","100%",document.getElementById("textarea01").value);
};

//===========================================================


function myCheckPreview(jsonobj) {
    CheckCreateFullPage(jsonobj);
    LODOP.PREVIEW();
};
function myCheckDesign(jsonobj) {
    CheckCreateFullPage(jsonobj);
    LODOP.PRINT_DESIGN();
};

function CheckCreateFullPage(jsonobj) {

    LODOP = getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    LODOP.PRINT_INITA("0mm","0mm","250mm","80mm","交通银行转账支票");

    LODOP.SET_PRINT_STYLE("FontColor","#0000FF");

    LODOP.ADD_PRINT_SHAPE(2,"0mm","0mm","250mm","80mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_SHAPE(2,"0mm","0mm","56.1mm","79.9mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_SHAPE(2,"25mm","68mm","145mm","9mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_SHAPE(2,"25mm","170mm","43mm","9mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_SHAPE(0,"29mm","170mm","43mm","0.3mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    var number=new Array("亿","千","百","十","万","千","百","十","元","角","分");

    for(var i=1;i<12;i++){
        LODOP.ADD_PRINT_SHAPE(1,"25mm",((170+(i*3.9))).toString()+"mm","0.3mm","9mm",0,1,"#804040");
        LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
        LODOP.ADD_PRINT_TEXT("25.6mm",((170+((i-1)*4))).toString()+"mm","6.6mm","5.3mm",number[i-1]);
        LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
        LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

        //add amount3
        LODOP.ADD_PRINT_TEXT("29.6mm",((170+((i-1)*4))).toString()+"mm","4mm","5.3mm",jsonobj.amount3[i-1].item.toString());
        LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
    }

    LODOP.ADD_PRINT_TEXT("6mm","19mm","25mm","10mm","   交通银行\n 转账支票存根");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
    LODOP.SET_PRINT_STYLEA(0,"LineSpacing","0.5mm");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("16mm","19mm","26.5mm","10mm","30103330\n01010755");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#000000");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
    LODOP.SET_PRINT_STYLEA(0,"FontName","System");
    LODOP.SET_PRINT_STYLEA(0,"LineSpacing","1mm");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("28mm","13mm","26.5mm","5.3mm","附加信息");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_SHAPE(0,"32mm","13mm","40mm","0.3mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_SHAPE(0,"37mm","13mm","40mm","0.3mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_SHAPE(0,"42mm","13mm","40mm","0.3mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("42.6mm","11mm","43mm","6mm","出票日期     年   月   日");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_SHAPE(2,"49mm","13mm","40mm","20mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_SHAPE(0,"59mm","13mm","40mm","0.3mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_SHAPE(0,"64mm","13mm","40mm","0.3mm",0,1,"#804040");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("49.5mm","13.2mm","13.2mm","5.3mm","收款人");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_TEXT("59.1mm","13.2mm","9.3mm","5.3mm","金额");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_TEXT("64.1mm","13.2mm","9.8mm","5.3mm","用途");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("69mm","13.2mm","41mm","5.3mm","单位主管         会计");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("4mm","94mm","69.3mm","7mm","交通银行转账支票");
    LODOP.SET_PRINT_STYLEA(0,"LetterSpacing","12");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"FontSize",13);
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("11.9mm","68mm","26.5mm","5.3mm","出票日期(大写)");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_TEXT("11.9mm","108mm","6mm","5.3mm","年");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_TEXT("11.9mm","125mm","6mm","5.3mm","月");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_TEXT("11.9mm","141mm","6mm","5.3mm","日");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("11.9mm","160.1mm","26.5mm","5.3mm","支付行名称");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("20mm","68mm","26.5mm","5.3mm","收款人");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("20mm","160mm","26.5mm","5.3mm","出票人帐号");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("26mm","69mm","26.5mm","9.3mm","人民币\n(大写)");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

    LODOP.ADD_PRINT_TEXT("39mm","68mm","26.5mm","5.3mm","用途");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_TEXT("45mm","68mm","26.5mm","5.3mm","上列款项请从");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_TEXT("52mm","68mm","26.5mm","5.3mm","我账户内支出");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
    LODOP.ADD_PRINT_TEXT("57mm","68mm","26.5mm","5.3mm","出票人签章");
    LODOP.SET_PRINT_STYLEA(0,"FontColor","#800000");
    LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);


    //add other
    LODOP.ADD_PRINT_TEXT("28.3mm","29.6mm","19.6mm","5.3mm",jsonobj.attach1);
    LODOP.ADD_PRINT_TEXT("33.6mm","12.7mm","39.7mm","5.3mm",jsonobj.attach2);
    LODOP.ADD_PRINT_TEXT("37.8mm","11.9mm","36.5mm","5.3mm",jsonobj.attach3);
    LODOP.ADD_PRINT_TEXT("42.1mm","24.9mm","10.8mm","5.3mm",jsonobj.year);
    LODOP.ADD_PRINT_TEXT("42.3mm","36.2mm","6.4mm","5.3mm",jsonobj.month);
    LODOP.ADD_PRINT_TEXT("42.3mm","43.1mm","5.6mm","5.3mm",jsonobj.day);
    LODOP.ADD_PRINT_TEXT("51.3mm","26.2mm","26.5mm","5.3mm",jsonobj.name);
    LODOP.ADD_PRINT_TEXT("59.5mm","22.8mm","26.5mm","5.3mm",jsonobj.amount1);
    LODOP.ADD_PRINT_TEXT("64.5mm","22.5mm","35mm","5.3mm",jsonobj.pop);
   
    LODOP.ADD_PRINT_TEXT("11.9mm","93.4mm","16.4mm","5.3mm",jsonobj.year1);
    LODOP.ADD_PRINT_TEXT("11.9mm","113.1mm","14mm","5.3mm",jsonobj.month1);
    LODOP.ADD_PRINT_TEXT("11.9mm","129.4mm","12.7mm","5.3mm",jsonobj.day1);
    LODOP.ADD_PRINT_TEXT("18.3mm","97.4mm","26.5mm","5.3mm",jsonobj.name);
    LODOP.ADD_PRINT_TEXT("27.8mm","96.8mm","70mm","5.3mm",jsonobj.amount2);
    LODOP.ADD_PRINT_TEXT("38.6mm","96.8mm","35mm","5.3mm",jsonobj.pop);
}

        
function digit_uppercase(n) {
    var fraction = ['角', '分'];
    var digit = [
    '零', '壹', '贰', '叁', '肆',
    '伍', '陆', '柒', '捌', '玖'
    ];
    var unit = [
    ['元', '万', '亿'],
    ['', '拾', '佰', '仟']
    ];
    var head = n < 0? '欠': '';
    n = Math.abs(n);
    var s = '';
    for (var i = 0; i < fraction.length; i++) {
        s += (digit[Math.floor(n * 10 * Math.pow(10, i)) % 10] + fraction[i]).replace(/零./, '');
    }
    s = s || '整';
    n = Math.floor(n);
    for (var i = 0; i < unit[0].length && n > 0; i++) {
        var p = '';
        for (var j = 0; j < unit[1].length && n > 0; j++) {
            p = digit[n % 10] + unit[1][j] + p;
            n = Math.floor(n / 10);
        }
        s = p.replace(/(零.)*零$/, '')
        .replace(/^$/, '零')
        + unit[0][i] + s;
    }
    return head + s.replace(/(零.)*零元/, '元')
    .replace(/(零.)+/g, '零')
    .replace(/^整$/, '零元整');
}

function prepareAmount(amount){
    var sAmounts = amount.split(".");
    var intAmount = sAmounts[0].split("");
    var floatAmount;
   
    if(sAmounts.length>1){
        sAmounts[1] = sAmounts[1]+'0';
        floatAmount = sAmounts[1].split("");
    } else {
        floatAmount = '00'.split("");
    }

    for (var i=0; i < floatAmount.length; i++){
        intAmount.push(floatAmount[i]);
    }

    var amount_length = intAmount.length;
    var amount3 = new Array();
    var check_length = 11;
    for(var j=0;j<check_length - amount_length -1;j++){
        amount3.push({
            'item':''
        });
    }
    amount3.push({
        'item':'￥'
    });
    for(var j=0;j<amount_length;j++){
        amount3.push({
            'item':intAmount[j]
            });
    }

    return amount3;
//    ￥
}

function ConverToDate(value) {
    var chinese = ['零','壹', '贰', '叁', '肆',
    '伍', '陆', '柒', '捌', '玖', '拾'];
   
    var result = "";
    for (var i = 0; i < y.length; i++) {
        result += chinese[y.charAt(i)];
    }
    result += "";
    if (m.length == 2) {
        if (m.charAt(0) == "1") {
            result += ("十" + chinese[m.charAt(1)] + "");
        }
    } else {
        result += (chinese[m.charAt(0)] + "");
    }
    if (d.length == 2) {
        result += (chinese[d.charAt(0)] + "十" + chinese[m.charAt(1)] + "");
    } else {
        result += (chinese[d.charAt(0)] + "");
    }
    return result;
}

function changeDateYear(year)//年转换大写
{
    var year1 = yearToBig(year.substring(0,1));
    var year2 = yearToBig(year.substring(1,2));
    var year3 = yearToBig(year.substring(2,3));
    var year4 = yearToBig(year.substring(3,4));
    return year1+year2+year3+ year4 ;
}

function changeDateMonth(month)//月转化大写
{
    return monthToBig(month);
}

function changeDateDay(day)//日转换大写
{
    document.writeln(dayToBig(day) + "    ");
}

function dayToBig(day){
    var r2 = parseInt(day);
    var day;

    if(r2==1) day="零壹";
    if(r2==2) day="零贰";
    if(r2==3) day="零叁";
    if(r2==4) day="零肆";
    if(r2==5) day="零伍";
    if(r2==6) day="零陆";
    if(r2==7) day="零柒";
    if(r2==8) day="零捌";
    if(r2==9) day="零玖";

    if(r2==11) day="壹拾壹";
    if(r2==12) day="壹拾贰";
    if(r2==13) day="壹拾叁";
    if(r2==14) day="壹拾肆";
    if(r2==15) day="壹拾伍";
    if(r2==16) day="壹拾陆";
    if(r2==17) day="壹拾柒";
    if(r2==18) day="壹拾捌";
    if(r2==19) day="壹拾玖";
    if(r2==10) day="零壹拾";

    if(r2==21) day="贰拾壹";
    if(r2==22) day="贰拾贰";
    if(r2==23) day="贰拾叁";
    if(r2==24) day="贰拾肆";
    if(r2==25) day="贰拾伍";
    if(r2==26) day="贰拾陆";
    if(r2==27) day="贰拾柒";
    if(r2==28) day="贰拾捌";
    if(r2==29) day="贰拾玖";
    if(r2==20) day="零贰拾";


    if(r2==31) day="叁拾壹";
    if(r2==30) day="零叁拾";
    return day;

}

function monthToBig(month){//月转化大写

    var  month = parseInt(month);
    var mon;
    if(month==1) mon="零壹";
    if(month==2) mon="零贰";
    if(month==3) mon="叁";
    if(month==4) mon="肆";
    if(month==5) mon="伍";
    if(month==6) mon="陆";
    if(month==7) mon="柒";
    if(month==8) mon="捌";
    if(month==9) mon="玖";
    if(month==10) mon="零壹拾";
    if(month==11) mon="壹拾壹";
    if(month==12) mon="壹拾贰";

    return  mon;

}

function yearToBig(year){
    jiaow=year;
    if(jiaow==1) year="壹"
    if(jiaow==2) year="贰"
    if(jiaow==3) year="叁"
    if(jiaow==4) year="肆"
    if(jiaow==5) year="伍"
    if(jiaow==6) year="陆"
    if(jiaow==7) year="柒"
    if(jiaow==8) year="捌"
    if(jiaow==9) year="玖"
    if(jiaow==0) year="零"
    return year;
}
