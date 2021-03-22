<?php

include('env.php');
include('header.php');
$now = time();


$templateID = $_POST['SPTemplates'];


if ($templateID == ""){
  $templateHTML = $_SESSION['templateHTML'];
  $templateTEXT = $_SESSION['templateTEXT'];
  $templateAMP = $_SESSION['templateAMP'];
  $templateSET = $_SESSION['templateSET'];
  $subvars = $_SESSION['subvars'];

}
else {

  // Get the tempalte details

  $url = "https://".$apidomain."/api/v1/templates/$templateID";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_POST, FALSE);
  //curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: $apikey"
  ));
  $response = curl_exec($ch);
  curl_close($ch);


  $templateData = json_decode($response,true);
  $templateHTML = $templateData["results"]["content"]["html"];
  $templateTEXT = $templateData["results"]["content"]["text"];
  $templateAMP  = $templateData["results"]["content"]["amp"];

  $_SESSION['templateHTML'] = "$templateHTML";
  $_SESSION['templateTEXT'] = "$templateTEXT";
  $_SESSION['templateAMP'] = "$templateAMP";

  foreach ($templateData as $a => $b){
        foreach ($b as $c => $d){
          if (($c !="options") and ($c != "content")){
            if (!$d) {$d="&nbsp; ";}
//            echo "<tr><td>$c</td><td><input type=text name=$c value=\"". $d ."\" asize=30></td></tr>";
$$c = "$d";
$_SESSION[$c] = "$d";
$templateSET[$c] = "$d"; 

          }
          if (($c == "options") OR ($c == "content")){
            foreach ($d as $e => $f){
              if ($e == "from"){
                foreach ($f as $g => $h){
//                  echo "<tr><td>from_.$g</td><td><input type=text name="from_.$g." value=".$h." size=30></td></tr>";
$$g = "$h";
$_SESSION['from_'.$g] = "$h";
$templateSET['from_'.$g] = "$h"; 


                }
              }
              if (($e !="text") and ($e != "html") and ($e != "from")){
//                echo "<tr><td>$e</td><td><input type=text name=".$e." value=".$f." size=30></td></tr>";
$$e = "$f";
$_SESSION[$e] = "$f";
$templateSET[$e] = "$f"; 

              }
            }
          }
        }
     }

$_SESSION['templateSET'] = $templateSET;
  }

  

if ($templateID == ""){
  // Check to see if template exists in DB already
  // if not add it and return here
  // if it does, ask if user wants to update.
  // if completely new, ask for Owner
}
?>

<script>

function openTab(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

</script>

<!-- /***** Build out top tabs *****/ -->
<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'wysiwyg')" id="defaultTab">WYSIWYG</button>
  <button class="tablinks" onclick="openTab(event, 'html')">HTML</button>
  <button class="tablinks" onclick="openTab(event, 'text')">TEXT</button>
  <button class="tablinks" onclick="openTab(event, 'amp')">AMP</button>
  <button class="tablinks" onclick="openTab(event, 'vars')">SUBSTITUTIONS</button>
  <button class="tablinks" onclick="openTab(event, 'params')">SETTINGS</button>
</div>


<!-- Tab content -->

<!--------------------------------------------------------------->
<!----------------- WYSIWYG TAB --------------------------------->
<!--------------------------------------------------------------->
<div id="wysiwyg" class="tabcontent">
<form name=f1 action="./savetemplate.php" method=post>
<input type=submit name=btnwysiwyg value="SAVE" onclick="postwysiwyg();">
  <input type=submit name=publish value="PUBLISH" onclick="postwysiwyg();">
  

  <!-- Include Quill stylesheet -->
  <link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">
 
  <script src="//cdnjs.cloudflare.com/ajax/libs/KaTeX/0.7.1/katex.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
  <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <script src="https://cdn.quilljs.com/1.0.0/quill.js"></script>
<script>
  function postwysiwyg(){

    // Get HTML content
    var html = quill.root.innerHTML;

    // Copy HTML content in hidden form
    $('#quillval').val( html )

    // Post form
    f1.submit();

  }

</script>

  <!-- Create the toolbar container -->
  <div id="toolbar-container">
    <span class="ql-formats">
      <select class="ql-font"></select>
      <select class="ql-size"></select>
    </span>
    <span class="ql-formats">
      <button class="ql-bold"></button>
      <button class="ql-italic"></button>
      <button class="ql-underline"></button>
      <button class="ql-strike"></button>
    </span>
    <span class="ql-formats">
      <select class="ql-color"></select>
      <select class="ql-background"></select>
    </span>
    <span class="ql-formats">
      <button class="ql-script" value="sub"></button>
      <button class="ql-script" value="super"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-header" value="1"></button>
      <button class="ql-header" value="2"></button>
      <button class="ql-blockquote"></button>
      <button class="ql-code-block"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-list" value="ordered"></button>
      <button class="ql-list" value="bullet"></button>
      <button class="ql-indent" value="-1"></button>
      <button class="ql-indent" value="+1"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-direction" value="rtl"></button>
      <select class="ql-align"></select>
    </span>
    <span class="ql-formats">
      <button class="ql-link"></button>
      <button class="ql-image"></button>
      <button class="ql-video"></button>
      <button class="ql-formula"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-clean"></button>
    </span>
  </div>
   <input type="hidden" name="id" id="id" value = "<?php echo $templateID; ?>">
<div id="form-container" class="container">

   <input type="hidden" name="quillval" id="quillval" value = "Placeholder"> 
  <!-- Create the editor container -->
  <div id="editor-container" class="container">
       <div class="row form-group">
         <input name="content" type="hidden">
         <div id="editor-container">
           <label for="content">
             <?php echo $templateHTML; ?>
           </label>
         </div>
       </div>
  </div>
</div>

 


  <!-- Initialize Quill editor -->
  <script>
    var quill = new Quill('#editor-container', {
      modules: {
        syntax: true,
        toolbar: '#toolbar-container'
      },
      theme: 'snow'
    });




quill.on('text-change', function(delta, source) {
	updateHtmlOutput()
})

<!--
// When the convert button is clicked, update output
$('#btn-convert').on('click', () => { updateHtmlOutput() })
-->

function getQuillHtml() { return quill.root.innerHTML; }

function updateHighlight() { hljs.highlightBlock( document.querySelector('#output-html') ) }

function updateHtmlOutput()
{
    let html = getQuillHtml();
    console.log ( html );
    document.getElementById('output-html').innerText = html;
    updateHighlight();
}


updateHtmlOutput()


// When the submit button is clicked, update output
$('#btn-submit').on('click', () => { 
    // Get HTML content
    var html = quill.root.innerHTML;

    // Copy HTML content in hidden form
    $('#quillval').val( html )

    // Post form
    myForm.submit();
})


  </script>
</div>






<!--------------------------------------------------------------->
<!-------------------- HTML TAB --------------------------------->
<!--------------------------------------------------------------->
<div id="html" class="tabcontent">
<!-- <form name=f2 action="./savetemplate.php" method=post> -->
<input type=submit name=btnhtml value="SAVE">
  <!-- Create the editor container -->
  <div id="editor1">
  <?php 
    echo '
      <textarea id="htmlval" name="htmlval" rows="45" cols="120">' . 
        $templateHTML
      . '</textarea>
   '; 
  ?>
  </div>
<!-- </form> -->
</div>


<!--------------------------------------------------------------->
<!-------------------- TEXT TAB --------------------------------->
<!--------------------------------------------------------------->
<div id="text" class="tabcontent">
<!-- <form name=f3 action="./savetemplate.php" method=post> -->
<input type=submit name=btntext value="SAVE">
  <!-- Create the editor container -->
  <div id="editor2">
  <?php 
    echo '
      <textarea id="textval" name="textval" rows="45" cols="120">' . 
        $templateTEXT
      . '</textarea>
   ';
  ?>
  </div>
<!-- </form> -->
</div>



<!--------------------------------------------------------------->
<!--------------------- AMP TAB --------------------------------->
<!--------------------------------------------------------------->
<div id="amp" class="tabcontent">
<!-- <form name=f4 action="./savetemplate.php" method=post> -->
<input type=submit name=btnamp value="SAVE">
  <!-- Create the editor container -->
  <div id="editor3">
  <?php
    echo '
      <textarea id="ampval" name="ampval" rows="45" cols="120">' .
        $templateAMP
      . '</textarea>
   ';
  ?>
  </div>
<!-- </form> -->
</div>


<!--------------------------------------------------------------->
<!----------------- VARIABLES TAB --------------------------------->
<!--------------------------------------------------------------->
<div id="vars" class="tabcontent">
<!-- <form name=f5 action="./savetemplate.php" method=post> -->
<input type=submit name=btnvars value="SAVE">
  <!-- Create the editor container -->
  <div id="editor2">

<?php
if ($subvars == ""){
  $subvars = '{
    "substitution_data": {},
    "metadata": {},
    "options": {}
  }';
  $_SESSION['subvars'] = "$subvars";
}
    echo '
      <textarea id="subvars" name="subvars" rows="45" cols="120">' .
        $subvars 
      . '</textarea>
   ';

?>
</div>      
<!-- </form> -->
</div>   

<!--------------------------------------------------------------->
<!----------------- SETTINGS TAB --------------------------------->
<!--------------------------------------------------------------->
<div id="params" class="tabcontent">
<!-- <form name=f6 action="./savetemplate.php" method=post> -->
<input type=submit name=btn_settings value="SAVE">
<table>
<?php  

  if ((sizeof($templateData)>1) AND (sizeof($templateSET)<1)){

    echo "im in your loop";

    $templateSET = "";
    foreach ($templateData as $a => $b){
        foreach ($b as $c => $d){
          if (($c !="options") and ($c != "content")){
            if (!$d) {$d="&nbsp; ";}
            echo "<tr><td>$c</td><td><input type=text name=\"$c\" value=\"". $d ."\" size=30></td></tr>";
            $templateSET[$c] = "$d";
        //    echo "<input type=hidden name=\"$c\" value=\"$d\" >";

          }
          if (($c == "options") OR ($c == "content")){
            foreach ($d as $e => $f){
              if ($e == "from"){
                foreach ($f as $g => $h){
                  echo "<tr><td>from_$g</td><td><input type=text name=\"from_".$g."\" value=\"".$h."\" size=30></td></tr>";
                  $templateSET['from_'.$g] = "$h";
          //        echo "<input type=hidden name=\"from_$g\" value=\"$h\" >";
                }
              }
              if (($e !="text") and ($e != "html") and ($e != "from")){
                echo "<tr><td>$e</td><td><input type=text name=\"".$e."\" value=\"".$f."\" size=30></td></tr>";
                $templateSET[$e] = "$f";
            //    echo "<input type=hidden name=\"$e\" value=\"$f\" >";
              }
            }
          }
        }
     } 
  }
  if ((sizeof($templateData)<1) AND (sizeof($templateSET)<1)){
    echo "loading new data set <br>";

        $templateSET['id'] = "sample-tempalte";
        $templateSET['name'] = "Sameple Template";	
        $templateSET['description'] = "This is the default template data";	
        $templateSET['published'] = "0";	
        $templateSET['click_tracking'] = "1";	
        $templateSET['transactional'] = "1";	
        $templateSET['open_tracking'] = "1";	
        $templateSET['shared_with_subaccounts'] = "0";	
        $templateSET['last_update_time'] = $now;
//"2021-01-25T23:13:58+00:00";
        $templateSET['last_use'] = $now;
        $templateSET['has_published'] = "0";	
        $templateSET['has_draft'] = "0";	
        $templateSET['from_email'] = "example@sparkpost.com";
        $templateSET['from_name'] = "CST Developer";
        $templateSET['subject'] = "This is a sample";
        $templateSET['reply_to'] = "";
        $templateSET['amp'] = "";

  }

  foreach ($templateSET as $i => $j){
    if (!$j) {$j="0";}
      echo "<tr><td>$i</td><td><input type=text name=$i value=\"". $j ."\" size=60></td></tr>";
  }


?>
</table>
</div>
</form>


<!-- Load the WYSIWYG Tab by default -->
<script>
  document.getElementById("defaultTab").click();
</script>




