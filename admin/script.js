window.onload = function()
{
  if(parseInt(getcookie('sidebarhide'))) { onsidebar(); }
  if(parseInt(getcookie('sectionmoduleshide'))) { onmodsection('sectionmodules'); }
  if(parseInt(getcookie('sectiontoolshide'))) { onmodsection('sectiontools'); }
  if(parseInt(getcookie('sectionstructhide'))) { onmodsection('sectionstruct'); }
  if(parseInt(getcookie('sectionaccesshide'))) { onmodsection('sectionaccess'); }
  if(parseInt(getcookie('sectionloghide'))) { onmodsection('sectionlog'); }
}
$(document).ready(function(){
    $('.table tr').mouseover(function(){
        $(this).addClass('hover');
    }).mouseout(function(){
        $(this).removeClass('hover');
    });
    
    $( "#datepicker" ).datepicker({
        showOn: "button",
        buttonImage: "/admin/images/calendar.png",
        buttonImageOnly: true,
        buttonText:''
    });
});

function onsidebar()
{
  var sidebar = null;
  var spliter = null;
  var workspace = null;
  sidebar = document.getElementById('sidebar');
  spliter = document.getElementById('spliter');
  workspace = document.getElementById('workspace');
  if(sidebar && spliter && workspace)
  {
    if(String(sidebar.style.display).toLowerCase()=='none')
    {
      setcookie('sidebarhide', '0');
      sidebar.style.display = 'block';
      sidebar.width = '29%';
      spliter.width = '1%';
      workspace.width = '70%'
    }
    else
    {
      setcookie('sidebarhide', '1');
      sidebar.style.display = 'none';
      sidebar.width = '1%';
      spliter.width = '1%';
      workspace.width = '98%'
    }
  }
}

function onmodsection(id)
{
  var section = null;
  section = document.getElementById(id);
  if(section)
  {
    if(String(section.style.display).toLowerCase()=='none')
    {
      section.style.display = 'block';
      setcookie(id+'hide', '0');
    }
    else
    {
      section.style.display = 'none';
      setcookie(id+'hide', '1');
    }

  }
}

function getcookie(name)
{
  var reg = new RegExp('(\;|^)[^;]*('+name+')\=([^;]*)(;|$)');
  var res = reg.exec(document.cookie);
  return (res!=null? unescape(res[3]) : null);
}

function onpanel()
{
  var o, i, n, a = onpanel.arguments;
  for(i = 0, n = a.length; i < n; i++)
  {
    o = document.getElementById(a[i]);
    if(!o) continue;
    if(String(o.style.display).toLowerCase()=='none')
    {
      o.style.display = 'block'; 
    }
    else
    {
      o.style.display = 'none';
    }
  }
}