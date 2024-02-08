const clientModel = require('../model/clients.model')
const bcrypt = require('bcrypt')
const generateToken = require('../../bblog/bblog-api/functions/generateToken')

const createUser = async (req, res) => {
          const { NOM, PRENOM, EMAIL, NUMERO_TEL, MOT_DE_PASSE, NUMERO_COUNTRY_ID } = req.body
          const LOGIN = NUMERO_TEL ?? EMAIL
          if(!NOM || NOM =='' || !PRENOM || PRENOM == '' || !LOGIN || LOGIN == '' || !MOT_DE_PASSE || MOT_DE_PASSE == '') {
                    const errors = { main: "Tous les champs sont obligatiores "}
                    res.status(400).json(errors)
          } else {
                    try {
                              const users = await clientModel.getClient(LOGIN, NUMERO_TEL ? 'NUMERO_TEL' : 'EMAIL')
                              if(users.length > 0)  {
                                        const errors = { main: "Cet utilisateur existe déjà "}
                                        res.status(400).json(errors)
                              } else {
                                        //crtyper le mot de passe
                                       const salt = await bcrypt.genSalt()
                                       const password = await bcrypt.hash(MOT_DE_PASSE, salt)
                                       const user = { NOM, PRENOM, EMAIL, NUMERO_TEL, NUMERO_COUNTRY_ID, MOT_DE_PASSE: password }
         
                                       //enregistre le client
                                       const newUser = await clientModel.createClient(user)
                                       const freshUser = { ...user, ID_CLIENT: newUser.insertId, }
                                       res.status(201).json({...freshUser, TOKEN: generateToken(freshUser, 3 * 24 * 60 * 60)})
                              }
                    } catch (error) {
                              console.log(error)
                              res.status(500).json({ errors: { main: "Erreur lors de l'enregistrement du client"}})
                    }
          }
}

const login = async (req, res) => {
          const { NUMERO_TEL, EMAIL, MOT_DE_PASSE } = req.body
          var errors = {}
          if(EMAIL == '' && (!NUMERO_TEL || NUMERO_TEL == '')) {
                    errors = {...errors, NUMERO_TEL: 'Le numéro de téléphone est vide' }
          }
          if(NUMERO_TEL == '' && (!EMAIL || EMAIL == '')) {
                    errors = {...errors, NUMERO_TEL: "L'email est vide" }
          }
          if(NUMERO_TEL != '' && MOT_DE_PASSE == '') {
                    errors = {...errors, MOT_DE_PASSE: 'Le mot de passe est vide' }
          }
          
          if(Object.keys(errors).length === 0 && errors.constructor === Object) { //vérifier si il y a des erreurs dans {errors}
                    const login = NUMERO_TEL ?? EMAIL
                    try {
                              const users = await clientModel.getClient(login, NUMERO_TEL ? 'NUMERO_TEL' : 'EMAIL')
                              const user  = users[0]
                              if(user) {
                                        if(MOT_DE_PASSE) {
                                                  const validPassword = await bcrypt.compare(MOT_DE_PASSE, user.MOT_DE_PASSE)
                                                  if(validPassword) {
                                                            res.status(200).json({
                                                                      ...user,
                                                                      TOKEN: generateToken({ NOM: user.NOM, PRENOM: user.PRENOM, NUMERO_TEL: user.NUMERO_TEL, MOT_DE_PASSE: user.MOT_DE_PASSE, ID_CLIENT: user.ID_CLIENT }, 3 * 24 * 60 * 60),
                                                                      login: true,
                                                            })
                                                  } else {
                                                            res.status(404).json({ login: false, error: 'Mot de passe incorrect' })
                                                  }
                                        } else {
                                                  res.status(200).json({ user })
                                        }
                              } else {
                                        const errors = { main: "L'utilisateur n'existe pas "}
                                        res.status(404).json(errors)
                              }
                    } catch (error) {
                              console.log(error)
                              res.status(500).json({ errors: { main: "Erreur lors de la connexion"}})
                    }
          } else {
                    res.status(400).json({ errors })
          }
}

module.exports = {
          createUser,
          login
}