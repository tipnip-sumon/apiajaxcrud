<script>
    const token = localStorage.getItem('api_token');
    if(!token){
        window.location.href = "api/";
    } 
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
   <div class="container mt-5">
        <div class="row">
            <div class="col-8 bg-primary text-white mb-4">
                <h1>All Posts</h1>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-8">
                <a href="/addpost" class="btn btn-sm btn-primary">Add New</a>
                <button class="btn btn-sm btn-danger" id="logoutBtn">Logout</button>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <div id="postContainer">
                    
                </div>
            </div>
        </div>
   </div>
<!--Simple Modal -->
<div class="modal fade" id="singlePostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
    aria-labelledby="singlePostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="singlePostModalLabel">Single Post</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!--Update Modal -->
<div class="modal fade" id="updatePostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
aria-labelledby="updatePostModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h1 class="modal-title fs-5" id="updatePostModalLabel">Update Post</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="updatePost">
        <div class="modal-body">
            <input type="hidden" class="form-control" name="" id="postId" value="">
            <b>Title</b>
            <input type="text" class="form-control" name="" id="postTitle" value="">
            <b>Description</b>
            <input type="text" class="form-control" name="" id="postDesc" value="">
            <b>Image</b>
            <img id="image" src="" width="100px" height="100px">
            <input type="file" class="form-control" name="" id="postImage">
        </div>
        <div class="modal-footer">
            <input type="submit" value="Save Changes" class="btn btn-primary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </form>
  </div>
</div>
</div>
   <!-- Latest compiled and minified JavaScript -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <script>
    document.querySelector("#logoutBtn").addEventListener('click',function(){
        const token = localStorage.getItem('api_token');
        fetch('/api/logout',{
            method:'POST',
            headers:{
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            localStorage.clear();
            window.location.href = "/api";
        });
    });
    function loadData(){
        const token = localStorage.getItem('api_token');
        fetch('/api/posts',{
            method:'GET',
            headers:{
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            var allposts = data.data.posts;
            const postContainer = document.querySelector("#postContainer");
            var tabledata = `<table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">View</th>
                            <th scope="col">Update</th>
                            <th scope="col">Delete</th>
                          </tr>`;
                        allposts.forEach(post => {
                            tabledata += `<tr>
                            <th><img src="/uploads/${post.image}" width="100px" height="100px"></th>
                            <td>${post.title}</td>
                            <td>${post.description}</td>
                            <td><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#singlePostModal" data-bs-postid="${post.id}">View</button></td>
                            <td><button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#updatePostModal" data-bs-postid="${post.id}">Update</button></td>
                            <td><button onclick="deletePost(${post.id})" type="button" class="btn btn-sm btn-danger">Delete</button></td>
                          </tr>`;
                        });
                        
            tabledata += `</table>`;
            postContainer.innerHTML = tabledata;
            
        });
    }
    loadData();
    const singleModal = document.querySelector('#singlePostModal');
    if (singleModal) {
        singleModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const modalBody = document.querySelector("#singlePostModal .modal-body");
        modalBody.innerHTML = "";
        const id = button.getAttribute('data-bs-postid');
        const token = localStorage.getItem('api_token');
        fetch(`/api/posts/${id}`,{
            method:'GET',
            headers:{
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            const post = data.data.post;
            modalBody.innerHTML = `
                Title : ${post.title}
                <br>
                Description : ${post.description}
                <br>
                <img width="100px" height="100px" src="/uploads/${post.image}">
            `
        });
    })
    }
    //update View
    const updatePostModal = document.querySelector('#updatePostModal');
    if (updatePostModal) {
        updatePostModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget

            const id = button.getAttribute('data-bs-postid');
            const token = localStorage.getItem('api_token');
            fetch(`/api/posts/${id}`,{
                method:'GET',
                headers:{
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => response.json())
            .then(data => {
                const post = data.data.post;
                console.log(post);
                document.querySelector("#postId").value = post.id;
                document.querySelector("#postTitle").value = post.title;
                document.querySelector("#postDesc").value = post.description;
                document.querySelector("#image").src = `uploads/${post.image}`;
                
            });
        })
    }
    //update post
    var updatePost = document.querySelector("#updatePost");
        updatePost.onsubmit = async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('api_token');
            const id = document.querySelector("#postId").value;
            const title = document.querySelector("#postTitle").value;
            const description = document.querySelector("#postDesc").value;
            
            // console.log(token);
            var formData = new FormData();
                formData.append('title',title);
                formData.append('description',description);
                // formData.append('image',image);
                // console.log(...formData);
                if(!document.querySelector("#postImage").files[0]==""){
                    const image = document.querySelector("#postImage").files[0];
                    formData.append('image',image);
                }
            let response = await fetch(`/api/posts/${id}`,{
                                method:'POST',
                                body: formData,
                                headers:{
                                    'Authorization': `Bearer ${token}`,
                                    'X-HTTP-Method-Override':'PUT'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                window.location.href = "/allposts";
                            });

        }
        //delete post
        async function deletePost(postId){
            const token = localStorage.getItem('api_token');
            let response = await fetch(`/api/posts/${postId}`,{
                                method:'DELETE',
                                headers:{
                                    'Authorization': `Bearer ${token}`
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                // console.log(data);
                                window.location.href = "/allposts";
                            });
        }
   </script>
</body>
</html>