import React, { useState , useEffect } from 'react';
import '../styles/login2.css';

const Login = ({ onLoginSuccess }) => {
  const [usuario, setUsuario] = useState('');
  const [clave, setClave] = useState('');

  const handleLogin = () => {
    if (usuario === 'admin' && clave === '1234' || true) {
      onLoginSuccess();
    } else {
      alert('Credenciales incorrectas');
    }
  };
/* para que solo en el login sea fondo negro*/
  useEffect(() => {
  document.body.classList.add("login2-bg");
  return () => {
    document.body.classList.remove("login2-bg");
  };
}, []);

  return (
<div className="login2-wrapper">
    <div className="ring">
        <i style={{ "--clr": "#544a7d" }}></i>
        <i style={{ "--clr": "#4DA3FF" }}></i>
        <i style={{ "--clr": "#FFE6AC" }}></i>
        <div className="login">
            <h2>Iniciar Sesión</h2>
            <div className="inputBx">
                <input type="text" placeholder="Usuario" required value={usuario} onChange={e => setUsuario(e.target.value)} />
            </div>
            <div className="inputBx">
                <input type="password" placeholder="Contraseña" required value={clave} onChange={e => setClave(e.target.value)} />
            </div>
            <div className="inputBx">
              
                <input type="submit" value="Ingresar" onClick={handleLogin} />
            </div>
        </div>
    </div>
</div>  
  );
};

export default Login;