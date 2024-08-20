<script>
    const token = localStorage.getItem('api_token');
    if(token){
        window.location.href = "/allposts";
    }
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
   <div class="container mt-5">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                          </div>
                          <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                          </div>
                          <button id="loginButton" class="btn btn-primary">Login</button>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
   </div>
   <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
   <script>
    $(document).ready(function(){
        $("#loginButton").on('click',function(){
            const email = $("#email").val();
            const password = $("#password").val();
            $.ajax({
                url:'/api/login',
                type:'POST',
                contentType:'application/json',
                data: JSON.stringify({
                    email:email,
                    password:password
                }),
                success:function(response){
                    console.log(response)
                    $(".card-footer").text(response.message);
                    localStorage.setItem('api_token',response.data.token);
                    window.location.href = "/allposts";
                },
                error:function(xhr,status,error){
                    $(".card-footer").text(xhr.responseJSON.data['error']);
                    console.log('Error:' + xhr.responseText);
                }
            });
        });
    });
   </script>
</body>
</html>