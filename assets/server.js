const express = require('express');
const oracledb = require('oracledb');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

// Update with your actual Oracle connection details
const dbConfig = {
  user: 'lzivoder',
  password: 'koliko99',
  connectString: 'db.vub.zone:1521/xe' // or your TNS/Service name
};

app.get('/students', async (req, res) => {
  let connection;
  try {
    connection = await oracledb.getConnection(dbConfig);
    const result = await connection.execute('SELECT * FROM students');
    res.json(result.rows);
  } catch (err) {
    console.error(err);
    res.status(500).send('Database error');
  } finally {
    if (connection) await connection.close();
  }
});

app.listen(3000, () => {
  console.log('Server running on http://localhost:3000');
});
