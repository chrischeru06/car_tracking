const express = require('express')
const communesController = require('../controller/communes.controller')

const communesRouter = express.Router()

communesRouter.get('/:provinceId', communesController.getAll)

module.exports = communesRouter