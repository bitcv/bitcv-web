import JSEncrypt from 'jsencrypt'

const EncryptPk = '-----BEGIN PUBLIC KEY-----\nMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwFkLedcBio8tr5l8U4HgAVLI1\n9TZ70Y4bDD0jOetPzBEOrWxlx8aKzgvzMx1GMVOd6vVsITcb5//ZKdjWppFaX70G\nxkfVHQspcL3b3G4Gcof62TvgPelW8zMBfF+YG17sBG4nTfZB/oshB4pZR0QLWqYR\nl8sXIeBuopq3uNG3JQIDAQAB\n-----END PUBLIC KEY-----'

// RSA 加密方法
const utilsEncrypted = (text) => {
  let encrypt = new JSEncrypt()
  encrypt.setPublicKey(EncryptPk)
  return encrypt.encrypt(text)
}

export default utilsEncrypted
