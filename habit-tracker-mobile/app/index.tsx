import { Redirect } from "expo-router";
import { ActivityIndicator, View } from "react-native";
import { useAuth } from "../src/auth/AuthContext";

export default function Index() {
  const { user, isLoading } = useAuth();

  // czekamy aż AuthProvider sprawdzi token i ewentualnie pobierze /me
  if (isLoading) {
    return (
      <View style={{ flex: 1, justifyContent: "center", alignItems: "center" }}>
        <ActivityIndicator />
      </View>
    );
  }

  // jeśli nie ma usera -> login
  if (!user) {
    return <Redirect href="/login" />;
  }

  // jeśli jest user -> home
  return <Redirect href="/home" />;
}
