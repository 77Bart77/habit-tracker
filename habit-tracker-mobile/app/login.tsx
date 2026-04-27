import { useRouter } from "expo-router";
import { useState } from "react";
import { Image, Pressable, StyleSheet, Text, TextInput, View } from "react-native";
import { login } from "../src/api/auth";
import { useAuth } from "../src/auth/AuthContext";
import { AppBackground } from "../src/ui/AppBackground";
import { AppCard } from "../src/ui/AppCard";

const logo = require("../assets/images/logo2.png");

export default function LoginScreen() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [status, setStatus] = useState<string>("");

  const { signIn } = useAuth();
  const router = useRouter();

  async function handleLogin() {
    setStatus("Loguję...");
    try {
      const result = await login(email, password);
      await signIn(result.token);
      router.replace("/home")
      setStatus("Zalogowano");
    } catch (e) {
      setStatus("Błąd logowania: " + String(e));
    }
  }

  return (
    <AppBackground>
      <View style={styles.center}>
        <AppCard style={styles.card}>
          <View style={styles.logoWrap}>
            <Image source={logo} style={styles.logo} resizeMode="contain" />
          </View>

          <Text style={styles.title}>Logowanie</Text>

          <TextInput
            style={styles.input}
            value={email}
            onChangeText={setEmail}
            autoCapitalize="none"
            keyboardType="email-address"
            placeholder="Email"
            placeholderTextColor="rgba(255,255,255,0.6)"
          />

          <TextInput
            style={styles.input}
            value={password}
            onChangeText={setPassword}
            secureTextEntry
            placeholder="Hasło"
            placeholderTextColor="rgba(255,255,255,0.6)"
          />

          <Pressable style={styles.button} onPress={handleLogin}>
            <Text style={styles.buttonText}>Zaloguj</Text>
          </Pressable>

          {!!status && <Text style={styles.status}>{status}</Text>}
        </AppCard>
      </View>
    </AppBackground>
  );
}

const styles = StyleSheet.create({
  center: {
    flex: 1,
    justifyContent: "center",
  },
  card: {
    alignSelf: "stretch",
  },

  logoWrap: {
    alignItems: "center",
    marginBottom: 12,
  },
  logo: {
    width: 90,
    height: 90,
  },

  title: {
    fontSize: 28,
    fontWeight: "800",
    marginBottom: 16,
    textAlign: "center",
    color: "white",
  },
  input: {
    borderWidth: 1,
    borderColor: "rgba(255,255,255,0.18)",
    backgroundColor: "rgba(255,255,255,0.10)",
    color: "white",
    paddingHorizontal: 12,
    paddingVertical: 12,
    borderRadius: 14,
    marginBottom: 10,
  },
  button: {
    paddingVertical: 12,
    borderRadius: 14,
    alignItems: "center",
    backgroundColor: "rgba(255,255,255,0.18)",
    borderWidth: 1,
    borderColor: "rgba(255,255,255,0.22)",
    marginTop: 6,
  },
  buttonText: {
    fontSize: 16,
    fontWeight: "700",
    color: "white",
  },
  status: {
    marginTop: 12,
    textAlign: "center",
    color: "rgba(255,255,255,0.9)",
  },
});