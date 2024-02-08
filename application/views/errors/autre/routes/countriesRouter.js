const express = require('express')
const countriesController = require('../controller/countries.controller')

const countriesRouter = express.Router()

countriesRouter.get('/', countriesController.getAll)

module.exports = countriesRouter