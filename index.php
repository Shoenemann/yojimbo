<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yojimbo Calculator</title>
  <style>
    /* Stili di base */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    
    /* Stili per il contenitore principale */
    .container {
      max-width: 960px;
      margin: 0 auto;
      padding: 20px;
    }
    
    /* Stili per l'intestazione */
    header {
      text-align: center;
      padding: 20px 0;
    }
    
    /* Stili per la navigazione */
    nav {
      background-color: #ffffff;
      color: #1d1d1d;
      margin-top:-35px;
      padding: 10px;
    }
    
    nav ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
    }
    
    nav ul li {
      margin: 0 10px;
    }
    
    nav ul li a {
      color: #141414;
      text-decoration: none;
    }
    
    nav ul li a:hover {
      color: #5e9cde;
    }
    
    /* Stili per il contenuto principale */
    main {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: flex-start;
    }
    
    /* Stili per la barra laterale */
    aside {
      flex: 0 0 100%;
      max-width: 100%;
      padding: 10px;
      box-sizing: border-box;
    }

    table { 
      text-align: center; 
      width: 100%;
    }
    
    /* Stili per il piè di pagina */
    footer {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
    }
    
    #container_table {
      max-width: 100%;
      max-height: 400px;
      overflow: auto;
    }
    .progress-bar {
      max-width: 100%;
      background-color: #e0e0e0;
      height: 20px;
      position:relative;
    }
    .inner { position:absolute; margin:0 auto; width:100%;}
    .inner:after { content:"%";}

    .progress {
      height: 100%;
    }
    .progress:before {
      content: "";
      display: block;
      height: 100%;
      background-color: #ff7777;
    }
        
    /* Media query per dispositivi mobili */
    @media (max-width: 600px) {
      .container {
        padding: 10px;
      }
      
      nav ul {
        flex-direction: column;
      }
      
      aside {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }
    
    h1 {
      text-align: left;
      margin: 0;
      padding: 0;
    }
  </style>
  
  <script>
            $(document).ready(function() {
              console.log("The document is ready!");
              $('form *').on('change', function() {
                  console.log("Form data changed!");
                  $(this).closest('form').submit();
              });
              $( "form" ).submit(function( event ) {
                  console.log("Form submit!");
                  event.preventDefault();
                  var dataForm =  $(this).serialize();
                  console.log("dataForm",dataForm);

                  // https://www.w3schools.com/jquery/ajax_getjson.asp
                  // $(selector).getJSON(url,data,success(data,status,xhr))
                  // mancava il secondo argomento!
                  $.getJSON("json.php", dataForm, function(data) {
                    console.log("JSON received!");
                    console.log("dataJSON", data);
                    generateTable(data);
                  });
              });
            });
          </script>
</head>
<body>
  <div class="container">
    <header>
      <h1>Yojimbo Calculator</h1>
      <nav>
        <ul>
          <li></li>
          <li></li>
          <li></li>
        </ul>
      </nav>
    </header>
    

    <form class="form" method = "get">
      <main>
        
        <aside>
          <div>
            <label for="choice">Scelta iniziale</label>
            <input type = "radio" id="choice1" value=1 name="choice">
              <label for="choice1">Invocatore</label>
            <input type = "radio" id="choice2" value=2 name="choice">
              <label for="choice2">Mostri</label>
            <input type = "radio" id="choice3" value=3 name="choice" checked = "checked">
              <label for="choice3">Boss</label>
          </div>
        </aside>

        <aside>
          <div>
            <label for="versione">Versione gioco</label>
            <input type = "radio" id="versioneNTSC" value=0 name="versione">
              <label for="versioneNTSC">NTSC/Japaanese</label>
            <input type = "radio" id="versionePAL" value=1 name="versione" checked="checked">
              <label for="versionePAL">PAL/HD_remastered</label>
          </div>
        </aside>

        <aside>
          <div>
            <label for="turbo">Turbo</label>
            <input type="checkbox" id="turbo" name="turbo" value="Turbo">
          </div>
        </aside>
    
        
        <aside>
          <div>
            <label for="affinita">Affinità</label>
            <input type="range" min="0" max="255" value="128" step="1" id="affinita" name="affinita" oninput = "this.nextElementSibling.value = this.value">
            <output>128</output>
          </div>
        </aside>

        <aside>
          <div>
            <label for="percent_guil">Percentuale soldi totali pagati a Yojimbo</label>
            <input type="range" min="0" max="100" value="50" step="1" id="percent_guil" name="percent_guil" oninput = "this.nextElementSibling.value = this.value">
            <output>50</output>%
          </div>
        </aside>
        
        <aside>
          <div>
            <label for="livellomostro">Livello mostro</label>
            <input type="range" min="1" max="6" value="1" step="1" id="livellomostro" name="livellomostro" oninput = "this.nextElementSibling.value = this.value">
            <output>1</output>
            <!---<select id="livellomostro" name="livellomostro">
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
            </select>--->
          </div>
        </aside>
        
        <aside>
          <div id="container_table">         
            <table id="myTable">
              <thead>
                <tr>
                  <th>Guil</th>
                  <th>Training</th>
                  <th>Motivation</th>
                  <th>Daigoro</th>
                  <th>Kozuka</th>
                  <th>Wakizashi</th>
                  <th>Wakizashi_all</th>
                  <th>Zanmato</th>
                </tr>
              </thead>
              <tbody>
                <!-- Il contenuto della tabella verrà generato dinamicamente qui -->
              </tbody>
            </table>
          </div>
        </aside>
      </main>
     </form>
  </div>
</body>
<script>
            var url = "json.php";
            
            // Funzione per generare la tabella dal JSON
            function generateTable(data) {
              console.log("Generate table function called!");
              var tableBody = $('#myTable tbody');
              $('#myTable tbody').html("");
              
              for (var i = 0; i < data.length; i++) {
                var row = $('<tr>');
                row.append('<td>' + data[i].guil_min + '</td>');
                row.append('<td>' + data[i].average_compatibility_increment.toFixed(2) + '</td>');
                row.append('<td>' + data[i].motivation + '</td>'); 
                row.append('<td  class="progress-bar"><div class="inner">'+data[i].daigoro.toFixed(1)+'</div><div class="progress" style="width:'+data[i].daigoro+'%"></div></td>');
                row.append('<td  class="progress-bar"><div class="inner">'+data[i].kozuka.toFixed(1)+'</div><div class="progress" style="width:'+data[i].kozuka+'%"></div></td>');
                row.append('<td  class="progress-bar"><div class="inner">'+data[i].wakizashi.toFixed(1)+'</div><div class="progress" style="width:'+data[i].wakizashi+'%"></div></td>');
                row.append('<td  class="progress-bar"><div class="inner">'+data[i].wakizashi_all.toFixed(1)+'</div><div class="progress" style="width:'+data[i].wakizashi_all+'%"></div></td>');
                row.append('<td  class="progress-bar"><div class="inner">'+data[i].zanmato.toFixed(1)+'</div><div class="progress" style="width:'+data[i].zanmato+'%"></div></td>');
                tableBody.append(row);
              }
            }
            
            
            // Caricamento  iniziale del JSON esterno utilizzando $.getJSON()
            data = "choice=3&versione=1&affinita=128&percent_guil=50&livellomostro=1";
            $.getJSON(url, data,function(result) {
              generateTable(result);
            });
  </script>
</html>
