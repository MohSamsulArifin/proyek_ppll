const products = [
    {
        id: 1,
        name: "Flawless Foundation",
        price: 130000,
        category: "Foundation",
        image: "image/Flawless Foundation.jpeg",
        description: `
Foundation dengan hasil natural dan tahan lama, diformulasikan untuk meratakan warna kulit tanpa terasa berat.

Manfaat:
- Menyamarkan pori dan noda secara natural
- Memberikan tampilan matte lembut sepanjang hari
- Tidak mudah cakey dan terasa ringan di kulit

Cara Pakai:
Aplikasikan tipis menggunakan sponge atau brush, mulai dari bagian tengah wajah.

Cocok untuk:
Semua jenis kulit, termasuk kulit sensitif.
`
    },

    {
        id: 2,
        name: "Cushion Foundation",
        price: 160000,
        category: "Foundation",
        image: "image/Cushion Foundation.webp",
        description: `
Cushion ringan yang memberikan hasil glowing sehat dan coverage menengah.

Manfaat:
- Membuat kulit tampak lebih cerah dan lembap
- Menghasilkan finish glowing namun tidak berminyak
- Mudah dibawa dan digunakan untuk touch-up

Cara Pakai:
Tekan puff secara perlahan lalu tap merata ke seluruh wajah.

Cocok untuk:
Kulit normal, kering, dan kombinasi.
`
    },

    {
        id: 3,
        name: "Glow BB Cream",
        price: 115000,
        category: "BB Cream",
        image: "image/Glow BB Cream.jpeg",
        description: `
BB Cream dengan tekstur ringan yang memberikan hasil natural glowing.

Manfaat:
- Meratakan warna kulit tanpa tampak tebal
- Mengandung moisturizer untuk kulit lebih lembap
- Cocok untuk penggunaan sehari-hari

Cara Pakai:
Aplikasikan ke wajah lalu ratakan dengan jari, sponge, atau brush.

Cocok untuk:
Kulit normal hingga kering.
`
    },

    {
        id: 4,
        name: "Sweet Blush On",
        price: 95000,
        category: "Blush On",
        image: "image/Sweet Blush On.webp",
        description: `
Blush on lembut dengan warna natural yang memberikan rona segar pada pipi.

Manfaat:
- Memberikan efek merona alami
- Tekstur halus dan mudah dibaurkan
- Tahan lama tanpa membuat kulit berminyak

Cara Pakai:
Sapukan tipis di area tulang pipi dengan brush.

Cocok untuk:
Semua jenis kulit.
`
    },

    {
        id: 5,
        name: "Perfect Line Eyeliner",
        price: 80000,
        category: "Eyeliner",
        image: "image/Perfect Line Eyeliner.jpeg",
        description: `
Eyeliner waterproof dengan ujung presisi yang mudah digunakan.

Manfaat:
- Warna hitam pekat sekali oles
- Tahan air dan keringat
- Cocok untuk tightline dan wing eyeliner

Cara Pakai:
Tarik garis tipis dari ujung dalam ke luar sesuai bentuk mata.

Cocok untuk:
Pemula hingga profesional.
`
    },

    {
        id: 6,
        name: "Soft Matte Powder",
        price: 110000,
        category: "Bedak",
        image: "image/Soft Matte Powder.avif",
        description: `
Bedak matte halus untuk tampilan bebas kilap sepanjang hari.

Manfaat:
- Mengontrol minyak berlebih
- Membuat kulit tampak halus dan lembut
- Tidak menyumbat pori

Cara Pakai:
Aplikasikan menggunakan brush atau sponge setelah foundation.

Cocok untuk:
Kulit berminyak dan kombinasi.
`
    },

    {
        id: 7,
        name: "Soft Compact Powder",
        price: 105000,
        category: "Bedak",
        image: "image/Soft Compact Powder.jpeg",
        description: `
Bedak compact ringan yang memberikan hasil matte natural.

Manfaat:
- Membantu mengunci makeup
- Tidak membuat kulit terasa kering
- Mudah dibawa untuk touch-up

Cara Pakai:
Tap ringan pada area wajah yang membutuhkan.

Cocok untuk:
Semua jenis kulit.
`
    },

    {
        id: 8,
        name: "Velvet Matte Lipstick",
        price: 75000,
        category: "Lipstick",
        image: "image/Velvet Matte Lipstick.webp",
        description: `
Lipstik matte lembut dengan warna intens dan tidak membuat bibir kering.

Manfaat:
- Pigmentasi tinggi sekali oles
- Tekstur creamy namun tetap matte
- Tahan lama dan ringan

Cara Pakai:
Aplikasikan langsung pada bibir atau gunakan lip brush.

Cocok untuk:
Penggunaan harian dan acara spesial.
`
    },

    {
        id: 9,
        name: "Shiny Lip Gloss",
        price: 60000,
        category: "Lip Gloss",
        image: "image/Shiny Lip Gloss.jpg",
        description: `
Lip gloss dengan efek berkilau yang membuat bibir tampak penuh.

Manfaat:
- Memberikan kilau natural
- Tidak lengket
- Dapat digunakan sendiri atau di atas lipstick

Cara Pakai:
Oleskan tipis di seluruh bibir.

Cocok untuk:
Semua jenis bibir.
`
    },

    {
        id: 10,
        name: "Volume Lash Mascara",
        price: 90000,
        category: "Mascara",
        image: "image/Volume Lash Mascara.jpeg",
        description: `
Maskara volumizing yang membuat bulu mata tampak lebih panjang dan tebal.

Manfaat:
- Memberi volume tanpa menggumpal
- Tahan air dan smudge-proof
- Brush melentikkan bulu mata secara natural

Cara Pakai:
Aplikasikan dari pangkal bulu mata ke atas.

Cocok untuk:
Bulu mata pendek maupun lurus.
`
    },

    {
        id: 11,
        name: "Glow Highlighter",
        price: 120000,
        category: "Highlighter",
        image: "image/Glow Highlighter.png",
        description: `
Highlighter dengan kilau lembut untuk tampilan glowing natural.

Manfaat:
- Memberikan efek radiant tanpa berlebihan
- Tekstur halus dan mudah dibaurkan
- Tahan lama

Cara Pakai:
Aplikasikan di tulang pipi, hidung, dan area yang ingin disorot.

Cocok untuk:
Semua jenis kulit.
`
    },

    {
        id: 12,
        name: "Natural Eyeshadow Palette",
        price: 150000,
        category: "Eyeshadow",
        image: "image/Natural Eyeshadow Palette.webp",
        description: `
Palet eyeshadow warna natural yang cocok untuk tampilan sehari-hari maupun glam.

Manfaat:
- Pigmentasi lembut namun buildable
- Warna mudah dibaurkan
- Cocok untuk pemula

Cara Pakai:
Gunakan warna matte untuk base dan shimmer untuk highlight.

Cocok untuk:
Semua tone kulit.
`
    },

    {
        id: 13,
        name: "Soft Touch Concealer",
        price: 85000,
        category: "Concealer",
        image: "image/Soft Touch Concealer.jpeg",
        description: `
Concealer lembut yang menutupi noda dan lingkar mata tanpa creasing.

Manfaat:
- Coverage medium-high
- Ringan dan mudah dibaurkan
- Tidak menggumpal sepanjang hari

Cara Pakai:
Beri titik kecil pada area yang ingin ditutupi lalu tap ringan.

Cocok untuk:
Kulit normal hingga berminyak.
`
    },

    {
        id: 14,
        name: "Bright Glow Serum",
        price: 180000,
        category: "Serum",
        image: "image/Bright Glow Serum.jpg",
        description: `
Serum brightening dengan kandungan Niacinamide dan Vitamin C.

Manfaat:
- Mencerahkan kulit kusam
- Membantu menyamarkan noda hitam
- Meningkatkan kekenyalan kulit

Cara Pakai:
Gunakan 3 tetes setelah toner, pijat hingga meresap.

Cocok untuk:
Kulit kusam dan tidak merata.
`
    },

    {
        id: 15,
        name: "Hydrating Face Mist",
        price: 100000,
        category: "Face Mist",
        image: "image/Hydrating Face Mist.avif",
        description: `
Face mist menyegarkan dengan aroma lembut yang melembapkan kulit.

Manfaat:
- Menghidrasi kulit kering
- Menyegarkan wajah sepanjang hari
- Dapat digunakan sebelum dan setelah makeup

Cara Pakai:
Semprotkan dari jarak 20 cm.

Cocok untuk:
Semua jenis kulit.
`
    },

    {
        id: 16,
        name: "Smooth Skin Primer",
        price: 95000,
        category: "Primer",
        image: "image/Smooth Skin Primer.jpg",
        description: `
Primer dengan tekstur halus yang membuat makeup lebih tahan lama.

Manfaat:
- Menyamarkan pori
- Mengurangi kilap
- Membuat foundation lebih smooth

Cara Pakai:
Gunakan sebelum foundation pada area T-zone.

Cocok untuk:
Kulit berminyak dan kombinasi.
`
    },

    {
        id: 17,
        name: "Pure Cleansing Oil",
        price: 130000,
        category: "Cleansing Oil",
        image: "image/Pure Cleansing Oil.webp",
        description: `
Cleansing oil lembut yang membersihkan makeup waterproof tanpa iritasi.

Manfaat:
- Melarutkan makeup berat
- Tidak membuat kulit kering
- Menjaga skin barrier

Cara Pakai:
Pijat pada wajah kering lalu bilas dengan air.

Cocok untuk:
Semua jenis kulit.
`
    },

    {
        id: 18,
        name: "Rose Water Toner",
        price: 90000,
        category: "Toner",
        image: "image/Rose Water Toner.webp",
        description: `
Toner dengan kandungan ekstrak mawar yang menenangkan kulit.

Manfaat:
- Menyegarkan wajah
- Menghidrasi kulit sebelum serum
- Mengurangi kemerahan ringan

Cara Pakai:
Tuang pada kapas atau tepuk langsung ke kulit.

Cocok untuk:
Kulit kering, sensitif, dan normal.
`
    },

    {
        id: 19,
        name: "Gentle Makeup Remover",
        price: 70000,
        category: "Makeup Remover",
        image: "image/Gentle Makeup Remover.jpeg",
        description: `
Makeup remover lembut yang efektif menghapus makeup tanpa membuat kulit iritasi.

Manfaat:
- Menghapus makeup ringan maupun berat
- Lembut di mata
- Tidak meninggalkan rasa lengket

Cara Pakai:
Tuang pada kapas lalu usapkan perlahan.

Cocok untuk:
Kulit sensitif.
`
    },

    {
        id: 20,
        name: "Soft Clean Facial Foam",
        price: 85000,
        category: "Facial Foam",
        image: "image/Soft Clean Facial Foam.jpg",
        description: `
Facial foam lembut yang membersihkan wajah tanpa membuat kulit kering.

Manfaat:
- Membersihkan minyak dan kotoran
- Busa lembut dan nyaman di kulit
- Tidak membuat wajah terasa tertarik

Cara Pakai:
Busakan dengan air lalu pijat lembut ke seluruh wajah.

Cocok untuk:
Kulit normal dan berminyak.
`
    },

    {
        id: 21,
        name: "Stay Fresh Setting Spray",
        price: 125000,
        category: "Setting Spray",
        image: "image/Stay Fresh Setting Spray.jpeg",
        description: `
Setting spray yang membuat makeup tahan lama dan tetap segar.

Manfaat:
- Mengunci makeup hingga berjam-jam
- Memberikan finish natural
- Mengurangi tampilan powdery

Cara Pakai:
Semprotkan merata setelah makeup selesai.

Cocok untuk:
Semua jenis makeup.
`
    },

    {
        id: 22,
        name: "Natural Brow Pencil",
        price: 70000,
        category: "Brow Pencil",
        image: "image/Natural Brow Pencil.jpg",
        description: `
Pensil alis natural yang mudah digunakan untuk membentuk alis rapi dan lembut.

Manfaat:
- Warna natural tidak berlebihan
- Tidak mudah luntur
- Mudah dibentuk dan dibaurkan

Cara Pakai:
Bingkai alis lalu isi bagian dalam dengan goresan halus.

Cocok untuk:
Pemula dan pengguna harian.
`
    }
];
