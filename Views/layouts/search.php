<style>
/* ========== PREMIUM HEADER NAVBAR INLINE (Bypass Cache) ========== */
.navbar-bg { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%) !important; }
.main-navbar { padding-top: 15px; }
.nav-link.nav-link-lg { color: #ffffff !important; transition: all 0.25s ease; }
.nav-link.nav-link-lg:hover { color: #eef2ff !important; transform: translateY(-2px); text-shadow: 0 0 10px rgba(255,255,255,0.4); }

.header-date-badge {
    background: rgba(255, 255, 255, 0.2) !important;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
    color: #ffffff !important;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.header-date-badge i, .header-date-badge span {
    color: #ffffff !important;
}

.header-lang-btn {
    background: rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
    border-radius: 8px;
    min-width: 40px;
    font-weight: 700;
    font-size: 12px;
    transition: all 0.25s ease;
    padding: 5px 10px;
    text-align: center;
    display: inline-block !important;
}

.header-lang-btn:hover {
    background: rgba(255, 255, 255, 0.3) !important;
    border-color: #ffffff !important;
    text-decoration: none;
    transform: translateY(-1px);
}

.header-lang-btn.active {
    background: #ffffff !important;
    color: #6366f1 !important;
    border-color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>

<div class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
    <div class="d-none d-sm-block">
        <div class="header-date-badge">
            <i class="far fa-calendar-alt"></i>
            <span id="header-live-clock">
                Loading...
            </span>
        </div>
    </div>
</div>
<script>
    // Live clock for header
    function updateClock() {
        <?php helper('cookie'); $local_lang = get_cookie('lang') ?: 'id'; ?>
        var d = new Date();
        var isIndo = <?= json_encode($local_lang === 'id') ?>;
        var days = isIndo 
            ? ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] 
            : ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        var months = isIndo 
            ? ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des']
            : ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        
        var day = days[d.getDay()];
        var date = d.getDate().toString().padStart(2, '0');
        var month = months[d.getMonth()];
        var year = d.getFullYear();
        var hr = d.getHours().toString().padStart(2, '0');
        var min = d.getMinutes().toString().padStart(2, '0');
        var sec = d.getSeconds().toString().padStart(2, '0');
        document.getElementById('header-live-clock').innerHTML = day + ', ' + date + ' ' + month + ' ' + year + ' - ' + hr + ':' + min + ':' + sec;
    }
    updateClock(); // Run immediately on load
    setInterval(updateClock, 1000); // Update every second
</script>