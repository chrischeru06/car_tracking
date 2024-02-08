const express = require('express')
const categoriesController = require('../controller/categories.controller')

const categoriesRouter = express.Router()

categoriesRouter.get('/', categoriesController.getAll)

module.exports = categoriesRouter