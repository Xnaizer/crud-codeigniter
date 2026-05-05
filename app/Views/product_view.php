<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Products | Classic Archive</title>

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
    max-width: 900px;
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
    padding: 8px 15px;
    border: 1px solid #1a1a1a;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.3s;
    cursor: pointer;
    background: transparent;
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

/* GRID PRODUCT */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

/* CARD ITEM */
.item {
    border: 1px solid #ddd;
    padding: 15px;
    text-align: center;
    background: #fff;
}

.item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.item p {
    margin: 10px 0;
    font-size: 14px;
}

.actions {
    margin-top: 10px;
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

<div class="main-card">
    <h2>Products</h2>

    <div style="margin-top:20px;">
        <button class="btn btn-black" onclick="openModal()">Create Product</button>
        <button class="btn" onclick="deleteAll()">Delete All</button>
    </div>

    <div id="list" class="grid"></div>
</div>

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-content">
        <h3>Create Product</h3>

        <form id="createForm">
            <input type="text" name="title" placeholder="Nama product" required>
            <input type="file" name="image" required>
            <button class="btn btn-black">Create</button>
        </form>

        <br>
        <button class="btn" onclick="closeModal()">Cancel</button>
    </div>
</div>

<script>
const API = "/api/images"
const token = localStorage.getItem("access_token")

if (!token) location.href="/auth/login"

function headers(){
    return { Authorization:"Bearer "+token }
}

function openModal(){
    document.getElementById("modal").style.display="flex"
}

function closeModal(){
    document.getElementById("modal").style.display="none"
}

document.getElementById("createForm").onsubmit = async e=>{
    e.preventDefault()

    const res = await fetch(API,{
        method:"POST",
        headers:headers(),
        body:new FormData(e.target)
    })

    const data = await res.json()

    if(res.ok){
        alert("Product berhasil dibuat")
        closeModal()
        e.target.reset()
        load()
    }else{
        alert("Gagal: "+(data.messages || data.message))
    }
}

async function load(){
    let r = await fetch(API,{headers:headers()})

    if(r.status==401) return location.href="/auth/login"

    let data = await r.json()

    let html=""

    data.forEach(x=>{
        html+=`
        <div class="item">
            <img src="/uploads/${x.deskripsi}">
            <p><b>${x.nama}</b></p>

            <div class="actions">
                <a href="/products/detail/${x.id}">
                    <button class="btn">Detail</button>
                </a>
                <button class="btn" onclick="del(${x.id})">Delete</button>
            </div>
        </div>
        `
    })

    document.getElementById("list").innerHTML=html
}

async function del(id){
    if(!confirm("Yakin hapus?")) return

    await fetch(API+"/"+id,{
        method:"DELETE",
        headers:headers()
    })

    alert("Deleted")
    load()
}

async function deleteAll(){
    if(!confirm("Hapus semua?")) return

    await fetch(API,{
        method:"DELETE",
        headers:headers()
    })

    alert("Semua data dihapus")
    load()
}

load()
</script>

</body>
</html>