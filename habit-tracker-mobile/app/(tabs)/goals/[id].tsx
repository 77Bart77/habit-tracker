import { useFocusEffect, useLocalSearchParams, useRouter } from "expo-router";
import { useCallback, useState } from "react";
import { ActivityIndicator, Alert, FlatList, Pressable, View } from "react-native";

import type { GoalDay, GoalDetails } from "../../../src/api/goals";
import { deleteGoal, getGoal, toggleDoneForDate } from "../../../src/api/goals";

import { AppBackground } from "../../../src/ui/AppBackground";
import { AppCard } from "../../../src/ui/AppCard";
import { AppHeader } from "../../../src/ui/AppHeader";
import { AppText } from "../../../src/ui/AppText";

export default function GoalDetailsScreen() {
  const router = useRouter();
  const params = useLocalSearchParams();

  const id = Number(params.id);

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [goal, setGoal] = useState<GoalDetails | null>(null);

  const today = new Date().toISOString().slice(0, 10);
  const todayDay = goal?.days?.find((d) => d.date === today);

  const load = useCallback(async () => {
    if (!Number.isFinite(id) || id <= 0) {
      setError("Nieprawidłowe ID celu.");
      setLoading(false);
      return;
    }

    setError("");
    setLoading(true);

    try {
      const res = await getGoal(id);
      setGoal(res);
    } catch (e: any) {
      setError(String(e?.message ?? e));
    } finally {
      setLoading(false);
    }
  }, [id]);

  useFocusEffect(
    useCallback(() => {
      load();
    }, [load])
  );

  async function onToggleToday() {
    if (!goal) return;

    try {
      await toggleDoneForDate(goal.id, today); // zwraca GoalDay, ale my i tak robimy reload
      await load();
    } catch (e: any) {
      setError(String(e?.message ?? e));
    }
  }

  function onDeleteGoal() {
    if (!goal) return;

    Alert.alert("Usunąć cel?", "Tej operacji nie da się cofnąć.", [
      { text: "Anuluj", style: "cancel" },
      {
        text: "Usuń",
        style: "destructive",
        onPress: async () => {
          try {
            await deleteGoal(goal.id);
            router.replace("/goals" as any);
          } catch (e: any) {
            setError(String(e?.message ?? e));
          }
        },
      },
    ]);
  }

  return (
    <AppBackground>
      <AppHeader
        title="Szczegóły celu"
        subtitle={goal?.title}
        rightActions={[
          { title: "Wróć", onPress: () => router.back() },
          { title: "Odśwież", onPress: load },
        ]}
      />

      {loading ? (
        <View style={{ flex: 1, justifyContent: "center", alignItems: "center" }}>
          <ActivityIndicator />
          <AppText variant="small" style={{ marginTop: 10 }}>
            Ładuję szczegóły…
          </AppText>
        </View>
      ) : (
        <>
          {!!error && (
            <AppText style={{ marginTop: 12, color: "crimson" }}>
              Błąd: {error}
            </AppText>
          )}

          {goal && (
            <FlatList<GoalDay>
              style={{ marginTop: 12 }}
              data={goal.days ?? []}
              keyExtractor={(d) => String(d.id)}
              ItemSeparatorComponent={() => <View style={{ height: 8 }} />}
              contentContainerStyle={{ paddingBottom: 150 }}
              ListHeaderComponent={
                <>
                  <AppCard>
                    <AppText variant="body" style={{ fontSize: 18, fontWeight: "800" }}>
                      {goal.title}
                    </AppText>

                    <AppText variant="small" style={{ marginTop: 6, opacity: 0.8 }}>
                      ID: {goal.id}
                    </AppText>

                    {!!goal.start_date && (
                      <AppText variant="small" style={{ marginTop: 6 }}>
                        Start: {String(goal.start_date).slice(0, 10)}
                      </AppText>
                    )}

                    {!!goal.end_date && (
                      <AppText variant="small" style={{ marginTop: 4 }}>
                        Koniec: {String(goal.end_date).slice(0, 10)}
                      </AppText>
                    )}
                  </AppCard>

                  <View style={{ marginTop: 12 }}>
                    {todayDay ? (
                      <AppCard>
                        <AppText>
                          Dziś ({today}): <AppText>{todayDay.status}</AppText>
                        </AppText>

                        <Pressable onPress={onToggleToday} style={{ marginTop: 10 }}>
                          <AppText style={{ fontWeight: "700" }}>
                            {todayDay.status === "done" ? "Cofnij wykonanie" : "Wykonano dziś"}
                          </AppText>
                        </Pressable>
                      </AppCard>
                    ) : (
                      <AppText variant="small" style={{ opacity: 0.7 }}>
                        Brak dnia zaplanowanego na dziś.
                      </AppText>
                    )}
                  </View>

                  <AppText variant="body" style={{ fontWeight: "800", marginTop: 16 }}>
                    Oś czasu
                  </AppText>

                  <View style={{ height: 10 }} />
                </>
              }
              renderItem={({ item }) => (
                <AppCard>
                  <View style={{ flexDirection: "row", justifyContent: "space-between" }}>
                    <AppText style={{ fontWeight: "700" }}>{item.date}</AppText>
                    <AppText style={{ opacity: 0.75 }}>{item.status}</AppText>
                  </View>
                </AppCard>
              )}
              ListEmptyComponent={
                <AppText variant="small" style={{ marginTop: 10, opacity: 0.7 }}>
                  Brak dni do wyświetlenia.
                </AppText>
              }
              ListFooterComponent={
                <AppCard style={{ marginTop: 16 }}>
                  <AppText variant="body" style={{ fontWeight: "800" }}>
                    Akcje
                  </AppText>

                  <View style={{ marginTop: 12, flexDirection: "row", gap: 10 }}>
                    <Pressable
                      onPress={() =>
                        router.push({
                          pathname: "/goal-edit",
                          params: { id: String(goal.id) },
                        } as any)
                      }
                      style={{
                        flex: 1,
                        paddingVertical: 12,
                        borderRadius: 14,
                        alignItems: "center",
                        backgroundColor: "rgba(255,255,255,0.12)",
                        borderWidth: 1,
                        borderColor: "rgba(255,255,255,0.18)",
                      }}
                    >
                      <AppText style={{ fontWeight: "800" }}>Edytuj</AppText>
                    </Pressable>

                    <Pressable
                      onPress={onDeleteGoal}
                      style={{
                        flex: 1,
                        paddingVertical: 12,
                        borderRadius: 14,
                        alignItems: "center",
                        backgroundColor: "rgba(220,38,38,0.18)",
                        borderWidth: 1,
                        borderColor: "rgba(220,38,38,0.35)",
                      }}
                    >
                      <AppText style={{ fontWeight: "800", color: "rgba(255,255,255,0.95)" }}>
                        Usuń
                      </AppText>
                    </Pressable>
                  </View>
                </AppCard>
              }
            />
          )}
        </>
      )}
    </AppBackground>
  );
}