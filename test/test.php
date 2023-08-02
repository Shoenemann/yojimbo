<html>
    <head>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>

        <form class = "form">
            <div>
                <select id="mode" name="modeee">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                </select>
            </div>
        </form>

        <div class = "test me">
            <p>Un testo</p>
            <div>
                <p>
                    <!-- contenuto da modificare dinamicamente -->
                </p>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('form *').on('change', function() {
                    $(this).closest('form').submit();
                });
                $( "form" ).submit(function( event ) {
                    event.preventDefault();
                    
                    var data =  $(this).serialize();
                    
                    $.getJSON("jsontest.php", function(data) {
                        console.log(data);
                        //generateContent(data);
                    });
                });
            });
        </script>



    </body>
</html>