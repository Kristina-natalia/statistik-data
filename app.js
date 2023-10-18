const express = require('express');
const bodyParser = require('body-parser');
const mongoose = require('mongoose');

const app = express();
const port = process.env.PORT || 3000;

mongoose.connect('mongodb://localhost/data_app', { useNewUrlParser: true, useUnifiedTopology: true });
const Item = mongoose.model('Item', { name: String });

app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());
app.use(express.static('public'));

app.get('/', async (req, res) => {
  const items = await Item.find();
  res.render('index', { items });
});

app.post('/add', async (req, res) => {
  const newItem = new Item({ name: req.body.name });
  await newItem.save();
  res.redirect('/');
});

app.get('/edit/:id', async (req, res) => {
  const item = await Item.findById(req.params.id);
  res.render('edit', { item });
});

app.post('/update/:id', async (req, res) => {
  await Item.findByIdAndUpdate(req.params.id, { name: req.body.name });
  res.redirect('/');
});

app.get('/delete/:id', async (req, res) => {
  await Item.findByIdAndRemove(req.params.id);
  res.redirect('/');
});

app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
// Fungsi perhitungan statistik
function calculateStatistics(items) {
    const values = items.map(item => parseFloat(item.name));
    const sum = values.reduce((a, b) => a + b, 0);
    const mean = sum / values.length;

    values.sort((a, b) => a - b);
    const middle = Math.floor(values.length / 2);
    const median = values.length % 2 === 0 ? (values[middle - 1] + values[middle]) / 2 : values[middle];

    const modeMap = {};
    let maxCount = 0;
    let modes = [];

    for (const value of values) {
        if (!modeMap[value]) modeMap[value] = 1;
        else modeMap[value]++;

        if (modeMap[value] > maxCount) {
            modes = [value];
            maxCount = modeMap[value];
        } else if (modeMap[value] === maxCount) {
            modes.push(value);
        }
    }

    const lowerBound = Math.min(...values);
    const upperBound = Math.max(...values);
    const zTable = {}; // Isi dengan data Z-table

    return { mean, median, modes, lowerBound, upperBound, zTable };
}
