function checkName()
{
  if (window.document.REPLIER.username.value == '')
  {
    alert('Please Enter Your Username');
    return true;
  }
  else
  {
    return false;
  }
}

function checkPass()
{
  if (window.document.REPLIER.password.value == '')
  {
    alert('Please Enter Your Password');
    return true;
  }
  else
  {
    return false;
  }
}

function checkTitle()
{
  if (window.document.REPLIER.subject.value == '')
  {
    alert('Please Enter Your Thread Title');
    return true;
  }
  else
  {
    return false;
  }
}

function checkPost()
{
  if (window.document.REPLIER.message.value == '')
  {
    alert('Please Enter Your Message');
    return true;
  }
  else
  {
    return false;
  }
}

function checkReciever()
{
  if (window.document.REPLIER.username.value == '')
  {
    alert('Please Enter The Recieving User');
    return true;
  }
  else
  {
    return false;
  }
}

function checkSubject()
{
  if (window.document.REPLIER.subject.value == '')
  {
    alert('Please Enter The Subject');
    return true;
  }
  else
  {
    return false;
  }
}