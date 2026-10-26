<?php
$page_title = "Hubungi Kami";
require_once dirname(__FILE__) . '/../includes/header.php';
?>

<div class="container">
    <h2 class="mb-4 text-center">Hubungi Kami</h2>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Kontak</h5>
                    
                    <div class="mb-4">
                        <h6><i class="fas fa-phone" style="color: #10b981;"></i> Telepon</h6>
                        <p>(021) 1234-5678</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6><i class="fas fa-envelope" style="color: #10b981;"></i> Email</h6>
                        <p><a href="mailto:info@apotek.com">info@apotek.com</a></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6><i class="fas fa-map-marker-alt" style="color: #10b981;"></i> Alamat</h6>
                        <p>Jl. Kesehatan No. 123<br>
                        Jakarta Pusat<br>
                        Indonesia 12000</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6><i class="fas fa-clock" style="color: #10b981;"></i> Jam Operasional</h6>
                        <p>Senin - Jumat: 08:00 - 17:00<br>
                        Sabtu: 08:00 - 13:00<br>
                        Minggu: Libur</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Kirim Pesan</h5>
                    
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="tel" class="form-control" id="phone">
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
