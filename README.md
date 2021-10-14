# Chiffrement RC4, Spritz, VMPC

Implémentation de l'algorithme de chiffrement RC4, Spritz, VMPC (ndrop)
intéropérable en Php et Javascript .

Utilisation intéréssante avec le modèle de transaction utilisé dans un autre [projet](https://github.com/PoujadeOlivier/Chiffrement_Vernam_Php_Javascript),<br/>
Rappel :<br/>
- utilisation d'un secret commun, entre le client et le serveur.<br/>
- Le secret sera utilisé comme grain de sel pour construire chaque clé de chiffrement.<br/>
La clé sera créée ou reconstituée coté serveur ou client.<br/>

Construction de la clé : <code> Hash( germe + ChaineAlétoire) </code> ou le germe est le secret commun.<br/>
Transitera sur le réseau la ChaineAlétoire et le Message chiffré.<br/>
