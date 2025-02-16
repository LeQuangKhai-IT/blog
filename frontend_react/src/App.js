import { Routes, Route, Link } from "react-router";
import Home from "./pages/Home/Home";
import LoginPage from "./pages/Login/Login";
import SignUpPage from "./pages/Signup/Signup";

const App = () => {
  return (
    <div className="App">
      <header className="App-header">Blog</header>
      <nav>
        <ul>
          <li>
            <Link to="/">Home</Link>
          </li>
          <li>
            <Link to="/login">Login</Link>
          </li>
          <li>
            <Link to="/register">Register</Link>
          </li>
        </ul>
      </nav>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<SignUpPage />} />
      </Routes>
    </div>
  );
};

export default App;
