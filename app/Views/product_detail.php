<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Product | Classic Archive</title>

<style>
body {
    font-family: 'Georgia', serif;
    background-color: #f4f4f4;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    justify-content: center;
    padding: 40px;
}

.main-card {
    background-color: #ffffff;
    padding: 50px;
    border-radius: 2px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.05);
    width: 100%;
    max-width: 600px;
    text-align: center;
    border: 1px solid #e0e0e0;
    position: relative;
}

.main-card::before {
    content: "";
    position: absolute;
    top: 10px; left: 10px; right: 10px; bottom: 10px;
    border: 1px solid #f0f0f0;
    pointer-events: none;
}

h2 {
    font-weight: 400;
    letter-spacing: 1px;
    border-bottom: 1px solid #1a1a1a;
    display: inline-block;
    padding-bottom: 5px;
}

.btn {
    text-decoration: none;
    color: #1a1a1a;
    padding: 10px 20px;
    border: 1px solid #1a1a1a;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.3s;
    cursor: pointer;
    background: transparent;
    margin: 5px;
}

.btn:hover {
    background-color: #1a1a1a;
    color: #fff;
}

.btn-black {
    background-color: #1a1a1a;
    color: #fff;
}

.btn-black:hover {
    background-color: #fff;
    color: #1a1a1a;
}

img {
    margin: 20px 0;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.4);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background:#fff;
    padding:30px;
    width:350px;
    border:1px solid #ddd;
    text-align:center;
}

input {
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-bottom:1px solid #ccc;
    outline:none;
}
</style>
</head>
<body>

<?php
$id = (string)($data['id'] ?? '');
$nama = (string)($data['nama'] ?? '');
$gambar = (string)($data['deskripsi'] ?? '');
?>

<div class="main-card">
    <h2>Detail Product</h2>

    <p><b><?= esc($nama) ?></b></p>
    <img src="/uploads/<?= esc($gambar) ?>" width="250">

    <div>
        <button class="btn btn-black" onclick="openModal()">Update</button>
        <button class="btn" onclick="deleteThis()">Delete</button>
    </div>

    <br>
    <a href="/products" class="btn">Back</a>
</div>

<!-- MODAL UPDATE -->
<div class="modal" id="modal">
    <div class="modal-content">
        <h3>Update Product</h3>

        <form id="updateForm">
            <input type="text" name="title" placeholder="Update title">
            <input type="file" name="image">
            <button class="btn btn-black">Update</button>
        </form>

        <br>
        <button class="btn" onclick="closeModal()">Cancel</button>
    </div>
</div>

<script>
const id = "<?= $id ?>"
const API = "/api/images/" + id
const token = localStorage.getItem("access_token")

if (!token) location.href="/auth/login"

function headers(){
    return { Authorization:"Bearer "+token }
}

/* MODAL */
function openModal(){
    document.getElementById("modal").style.display="flex"
}

function closeModal(){
    document.getElementById("modal").style.display="none"
}

/* UPDATE */
document.getElementById("updateForm").onsubmit=async e=>{
    e.preventDefault()

    const res = await fetch(API,{
        method:"POST",
        headers:headers(),
        body:new FormData(e.target)
    })

    if(res.ok){
        alert("Updated")
        location.reload()
    } else {
        alert("Gagal update")
    }
}

/* DELETE */
async function deleteThis(){
    if(!confirm("Yakin hapus product ini?")) return

    await fetch(API,{
        method:"DELETE",
        headers:headers()
    })

    alert("Deleted")
    location.href="/products"
}
</script>

</body>
</html>