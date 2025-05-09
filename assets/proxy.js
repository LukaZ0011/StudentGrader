const express = require('express');
const axios = require('axios');
const cors = require('cors');
const multer = require('multer');
const upload = multer(); // Initialize multer for parsing FormData
const app = express();

app.use(cors());

app.post('/proxy', upload.none(), async (req, res) => {
  console.log('Incoming request body:', req.body); // Debugging
  try {
    const response = await axios.post(
      'https://dev.vub.zone/sandbox/router.php',
      new URLSearchParams(req.body), // Convert req.body to URLSearchParams
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    res.send(response.data);
  } catch (err) {
    console.error('Error:', err.response?.data || err.message);
    res.status(err.response?.status || 500).send(err.response?.data || 'Proxy error');
  }
});

app.listen(3000, () => console.log('Proxy running on http://localhost:3000'));
