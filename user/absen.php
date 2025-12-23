<button onclick="absen()">Absen</button>
<p id="info"></p>
<a href="home_user.php">← Kembali</a>

<script>
function absen(){
  navigator.geolocation.getCurrentPosition(
    (pos)=>{
      fetch("proses_absen.php",{
        method:"POST",
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({
          lat:pos.coords.latitude,
          lng:pos.coords.longitude
        })
      })
      .then(r=>r.text())
      .then(r=>document.getElementById("info").innerText=r);
    },
    (err)=>{
      alert("Lokasi gagal: " + err.message);
    }
  );
}
</script>
