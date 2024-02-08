const express = require('express')
const provincesController = require('../controller/provinces.controller')

const provincesRouter = express.Router()

provincesRouter.get('/', provincesController.getAll)

module.exports = provincesRouter