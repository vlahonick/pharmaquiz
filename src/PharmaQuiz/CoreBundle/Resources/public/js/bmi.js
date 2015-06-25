function conv(aa)//Height conversion
{
  var ft=0, inc=0, ht=0;
  if(aa==1 || aa==2)
  {
    ft = document.bmi.opt2.value;
    inc = document.bmi.opt3.value;
    var ss = ft*12;
    var tot = ss+parseInt(inc);
        var val= tot*2.54;
    document.bmi.ht.value = Math.round(val);
  }

  else{
    ht = document.bmi.ht.value;
    if(ht!="")
    {
      var cm=Math.round(ht/2.54);
      var div=parseInt(cm/12);
      var md=cm%12;
      document.bmi.opt2.value=div;
      document.bmi.opt3.value=md;
    }
  }

}

function unit()  //Weight conversion
{
  var pp=document.bmi.opt1.value;
  var ww = document.bmi.wg.value;

  //Kilogram to pounds
  if(pp=="pounds")
  {
    document.bmi.wg.value = Math.round((ww*2.2)*100)/100;
  }

  //Pounds to kilograms
  else
  {
    document.bmi.wg.value=Math.round(ww/2.2);
  }
}