const express = require('express');
const bodyParser = require('body-parser');
const mongoose = require('mongoose');
const math = require('mathjs');
const Chart = require('chart.js');

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
    const name = req.body.name;
    const item = new Item({ name });
    await item.save();
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

app.post('/calculate', (req, res) => {
    const data = req.body.data;
    const numbers = data.split(',').map(Number);

    const mean = math.mean(numbers);
    const median = math.median(numbers);
    const mode = math.mode(numbers);

    res.json({ mean, median, mode });
});

app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
