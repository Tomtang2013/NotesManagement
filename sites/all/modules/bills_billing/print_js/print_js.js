var LODOP;

function myPreview(jsonobj) {
    CreatePrintPage(jsonobj);
    LODOP.PREVIEW();
};
function myDesign(jsonobj) {
    CreatePrintPage(jsonobj);
    LODOP.PRINT_DESIGN();
};

function CreatePrintPage(jsonobj) {
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

    //LODOP.ADD_PRINT_TEXT("53.7mm","24.9mm","26.5mm","5.3mm","公物赔偿");
    //LODOP.ADD_PRINT_TEXT("53.7mm","66.7mm","26.5mm","5.3mm","48.30");
    //LODOP.ADD_PRINT_TEXT("61.4mm","24.9mm","26.5mm","5.3mm","罚款");
    //LODOP.ADD_PRINT_TEXT("61.4mm","66.7mm","26.5mm","5.3mm","50.00");
    //LODOP.ADD_PRINT_TEXT("69.1mm","24.9mm","26.5mm","5.3mm","优惠费");
    //LODOP.ADD_PRINT_TEXT("69.1mm","66.7mm","26.5mm","5.3mm","-4.00");
    }

//LODOP.ADD_PRINT_TEXT("77.5mm","24.9mm","26.5mm","5.3mm","总计");
//LODOP.ADD_PRINT_TEXT("77.5mm","88.9mm","52.4mm","5.3mm","陆百柒拾捌元叁角零分");
//LODOP.ADD_PRINT_TEXT("77.3mm","66.7mm","18.5mm","5.3mm","678.30");
};
function myAddHtml() {
    LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    LODOP.PRINT_INIT("tobyqiu的打印");
    LODOP.ADD_PRINT_HTM(10,55,"100%","100%",document.getElementById("textarea01").value);
};	       