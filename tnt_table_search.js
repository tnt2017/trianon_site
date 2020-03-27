function MySearch(param)
{
    console.log('MySearch');
    //alert(param);
    if(param==true)
    {
        ulSearch();
    }
    else
    {
        tableSearch();
    }
    return;
}

function ulSearch()
{
    var phrase = document.getElementById('search-text');
    var ul = document.getElementById('ul-content');
    var regPhrase = new RegExp(phrase.value, 'i');

    $("ul.ulclass > li.liclass").each(function()
    {
        var txt = $(this).text();

        if(txt.toLowerCase().indexOf(phrase.value.toLowerCase())>-1)
        {
            //alert (txt);
            $(this).show();
        }
        else
        {
            $(this).hide();
        }
        //var flag = false;
        //flag = regPhrase.test(txt);
        //if(flag)
        //alert ('flag=' + flag);
    });
}



function tableSearch() 
{
    console.log('tableSearch');
    var tables = document.getElementsByClassName('myclass333');
    // ����� ����� ����� ����������� �� ���� ��������� � �����
    //debugger;

    for( var n=0; n<tables.length; n++ ) 
    {
        var table = tables[n];
        //debugger;
        //var table = document.getElementById('info-table');
        var phrase = document.getElementById('search-text');
        var regPhrase = new RegExp(phrase.value, 'i');
        var flag = false;
        for (var i = 1; i < table.rows.length; i++) 
        {
            flag = false;
            for (var j = table.rows[i].cells.length - 1; j >= 0; j--) 
            {
                flag = regPhrase.test(table.rows[i].cells[j].innerHTML);
                    if (flag) break;
            }
            if (flag) 
            {
                table.rows[i].style.display = "";
            } 
            else 
            {
            table.rows[i].style.display = "none";
            }
        }
        console.log('������ ' + n + ' �������');
    }
}




function search_prepare(idGrp,idChild) // ������������ ���� � �������� �������
 {
   var poisk_zapros=$('input[name=sTov]').val(); ////////////////////////////////////// ��� ����� ������
   var start_words;

  $.ajax({
    url: "start_words.php",
    dataType: "html",
    data: {'par': par
          },

    success: function(dat, stat,xmlReq) 
    {
      //alert(dat);    	
      start_words = JSON.parse(dat);  
      //alert(start_words[1]);
      //alert(start_words[2]);
      //let arr = ["����������", "��������", "�����", "������", "�������", "����"];
      //let json = JSON.stringify(arr);
      //alert(json); // �� �������� ������!

      if(poisk_zapros.indexOf(' ')>-1)
      {
        //alert('2 ����� � �������');
        var arrayOfStrings = poisk_zapros.split(' ');
        //alert('2� ����� = ' + arrayOfStrings[1]);

        for(var i=0;i<start_words.length;i++)
        {
          if(arrayOfStrings[1]==start_words[i])          
          {
            poisk_zapros=arrayOfStrings[1] + " " + arrayOfStrings[0];
            $('input[name=sTov]').val(poisk_zapros);           
            //alert('����� �� ������. ������ �������' + poisk_zapros);
            $('img[name=bnLsTovs]').click();

          }
        }
      }
      //_loadTovs(idGrp,idChild);

      //////////////////////////////////////////////////////////////////////////////////////////
    },
    complete: function(a,b)
    {
	  }
    });
 }