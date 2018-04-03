export const updateUserInfo = (state, payload) => {
  let userInfo = localStorage.getItem('userInfo')

  if (userInfo) {
    userInfo = JSON.parse(userInfo)
  }

  if (payload) {
    userInfo = payload
    localStorage.setItem('userInfo', JSON.stringify(userInfo))
  }

  state.userInfo = userInfo
}

export const cleanUserInfo = (state) => {
  localStorage.removeItem('userInfo')

  state.userInfo = null
}
