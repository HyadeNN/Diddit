# LinkedOut - Profesyonel Ağ Platformu

## Proje Amacı

LinkedOut, Facebook benzeri bir sosyal medya platformunun LinkedIn benzeri profesyonel bir ağa dönüştürülmesi projesidir. Amaç, sosyal medya özelliklerini korurken, profesyonel bağlantılar kurma, kariyer geliştirme ve iş arama özelliklerine sahip bir platform oluşturmaktır.

Proje, mevcut bir PHP tabanlı sosyal ağ uygulamasının hem veri yapısını hem de kullanıcı arayüzünü değiştirerek, kullanıcıların profesyonel profiller oluşturmasına, iş deneyimlerini ve eğitimlerini paylaşmasına, ve diğer profesyonellerle bağlantı kurmasına olanak sağlayan bir sistem oluşturmayı hedeflemektedir.

## Şu Ana Kadar Yapılanlar

### 1. Veritabanı Yapısında Değişiklikler
- `likes` tablosu `endorsements` olarak değiştirildi
- Yeni tablolar eklendi: `education`, `experiences`, `skills`
- Kullanıcı profillerine profesyonel bilgiler için yeni alanlar eklendi: `headline`, `company`, `job_title`
- Posts tablosuna `post_type` alanı eklendi

### 2. Sınıf Yapıları ve Kod Değişiklikleri
- User sınıfı güncellendi: `follow_user` -> `connect_user`
- Post sınıfı güncellendi: `like_post` -> `endorse_post`
- Geriye dönük uyumluluk eklendi: Eski `likes` tablosundan `endorsements` tablosuna geçiş
- Çakışan dosya isimleri ayrıldı (`post.php` -> `post_display.php`)

### 3. Arayüz ve Görsel Değişiklikler
- Renk şeması LinkedIn'in mavi tonlarına dönüştürüldü (#0a66c2)
- Logo ve marka ismi "DiddIt"ten "LinkedOut"a değiştirildi
- Tüm UI elementleri profesyonel bir görünüme kavuşturuldu
    - Butonlar, kartlar, menüler LinkedIn stilinde tasarlandı
    - Font aileleri LinkedIn'e benzer şekilde güncellendi

### 4. Terim Değişiklikleri
- "Like" -> "Endorse" (Onaylama)
- "Friend" -> "Connection" (Bağlantı)
- "Follow" -> "Connect" (Bağlan)

### 5. Yeni Özellikler ve Sayfalar
- Profil sayfaları profesyonel içerikle zenginleştirildi:
    - Deneyim, eğitim ve yetenekler için yeni profil bölümleri
    - Profil gücü göstergesi
- Ağ yönetimi için "My Network" sayfası
- İş ilanları için "Jobs" sayfası

### 6. Hata Düzeltmeleri
- Veritabanı bağlantı sorunları çözüldü
- Eksik dosya referansları düzeltildi
- Header ve navigasyon çubuğu hizalama sorunları giderildi
- Post gösterimi sorunları düzeltildi

## Yapılacak Kalan İşler

### 1. İçerik İyileştirmeleri
- Örnek veri ekleme: Gerçekçi iş deneyimleri, eğitim bilgileri ve yetenekler
- Profesyonel içerik önerileri ve şablonları
- Endüstri terimleri ve etiketler

### 2. Ek Özellikler
- Mesajlaşma sistemi tamamlanması
- İş ilanı oluşturma ve başvuru sistemi
- Yetenek onaylama (endorsement) sistemi geliştirme
- Tavsiye mektubu yazma özelliği

### 3. İleri Seviye Özellikler
- İçerik analizi ve profesyonel trend raporları
- Kariyer gelişimi için öneriler
- İş ve yetenek eşleştirme algoritması
- Premium üyelik sistemi

### 4. Optimizasyon ve Güvenlik
- Kod optimizasyonu ve performans iyileştirmeleri
- Güvenlik açıklarının taranması ve giderilmesi
- Veri gizliliği ve KVKK uyumluluğu
- SEO optimizasyonu

### 5. Test ve Kullanılabilirlik
- Kapsamlı test senaryoları
- Kullanıcı deneyimi (UX) iyileştirmeleri
- Farklı cihazlarda responsivite testleri
- Beta kullanıcı geri bildirimleri

## Teknolojiler

- PHP (Backend)
- MySQL (Veritabanı)
- HTML/CSS (Frontend)
- JavaScript (İstemci tarafı etkileşimler)

## Kurulum ve Çalıştırma

1. Repoyu klonlayın
2. Veritabanını oluşturun ve `mybook_db.sql` dosyasını içe aktarın
3. `classes/connect.php` dosyasında veritabanı bağlantı bilgilerini güncelleyin
4. Projeyi bir PHP sunucusunda çalıştırın (XAMPP, WAMP, vb.)

## Lisans

Bu proje kişisel eğitim amaçlıdır ve herhangi bir lisans içermemektedir.