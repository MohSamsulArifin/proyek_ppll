const express = require('express');
const app = express();

// PENTING: pakai port dari Railway
const PORT = process.env.PORT || 3000;

app.get('/', (req, res) => {
  res.send('<h1>WEB SUDAH JALAN ✅</h1>');
});

// HARUS listen ke 0.0.0.0
app.listen(PORT, '0.0.0.0', () => {
  console.log(`Server running on port ${PORT}`);
});
